﻿<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\ItSkills;
use app\models\JobSubCategory;
use app\models\TjDistrict;
use app\assets\AppAsset;

$this->title ='Easy Search - employer.takeajob.com';

 $this->registerJsFile(Yii::$app->request->baseUrl.'/js/jquery-ui.js',['depends' => [\yii\web\JqueryAsset::className()]]);
 $skill_data = ItSkills::find()->select('tj_skill_names')->orderBy('tj_skill_names')->asArray()->all();
	foreach($skill_data as $skills)
		$all_skills[] = $skills['tj_skill_names'];
	$all_skills = array_values(array_intersect_key($all_skills, array_unique(array_map('strtolower', $all_skills))));


$JobsubData = JobSubCategory::find()->all();
$JobsubData  = ArrayHelper::map($JobsubData ,'tj_jsc_id' ,'tj_jsc_name');

$locations = TjDistrict::find()->orderBy('tj_dt_districtname')->all();
$location_data = ArrayHelper::map($locations,'tj_dt_districtId','tj_dt_districtname');
     
		
foreach($location_data as $keys => $loc)	
	$search_locations['2-'.$keys] = $loc;

foreach($JobsubData as $k => $v)	
	$search_designation['1-'.$k] = $v;

foreach($all_skills as $key => $val)	
	$search_skills['0-'.$val] = $val;

$skills_designations_locations = array_merge($search_skills,$search_designation,$search_locations); 
?>
<style>
.error{
	color:#a94442;
}
</style>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <section class="tj-easy-search">
	<div class="container">
		<div class="row">
			<div class="col-md-9 tj-easy-search-design">
                <div class="search-resume-tab-holder">
                    <ul class="nav nav-tabs">					
                        <li><a href="<?php echo Url::to(['search/advanced_search']); ?>" >Advanced Search</a></li>                 
	                    <li class="active"><a href="<?php echo Url::to(['search/easy_search']); ?>">Easy Search</a></li>
	                    <li><a href="<?php echo Url::to(['search/special_search']); ?>" >Special Search</a></li>                   
	                    <li><a href="<?php echo Url::to(['search/role_search']); ?>" >Role Search</a></li> 
                    </ul>
                    <div class="tab-content">
                        <div id="easy-search" class="tab-pane fade in active">
                            <h4 class="easy-search-text"><i class="fa fa-tasks padding-right"></i>Easy Search</h4>                           
                            <div class="tj-easy-search-input">                               
                                <?php $form = ActiveForm::begin(['id'=>'search_form']);  ?>    
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Search By</label>
                                        <div class="col-sm-10">
										
										    <?php echo $form->field($ez_search_model,'ez_search_field')->textInput(['class' => 'form-control','placeholder' => 'Search by Skills, Designation, Location, Salary and Experience'])->label(false); 
                                        echo '<div id="search_error" class="error"></div>';
                                        echo  $form->field($ez_search_model,'search_keyword')->hiddenInput(['id' => 'search_keyword'])->label('Easy search Tips : php, Software Engineer, Mumbai, 1-10 years/yrs, 1-15 lakhs/lks/lpa ');?>
                                           
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Candidates Active in Last</label>
                                        <div class="col-sm-10">
                                            <?php
											$ez_search_model->cadidate_active_state = 180;
                                        $cadidate_active_state = array(
                                        '3' => '3 days','7' => '7 days','15' => '15 days',
                                        '30' => '30 days','60' => '2 months','90' => '3 months',
                                        '120' => '4 months','180' => '6 months','270' => '9 months',
                                        '365' => '1 Year','all' => 'All Resumes','4-7' => '4 days to 7 days',
                                        '8-15' => '8 days to 15 days','16-30' => '16 days to 30 days','30-60' => '30 days to 2 months',
                                        '30-90' => '30 days to 3 months ','60-90' => '2 months to 3 months','60-120' => '2 months to 4 months',
                                        '90-120' => '3 months to 4 months','90-180' => '3 months to 6 months','120-180' => '4 months to 6 months',
                                        '180-270' => '6 months to 9 months','180-365' => '6 months to 1 Year','270-365' => '9 months to 1 Year',
                                        '1 Year+' => '1 Year+');
                                        
                                        echo $form->field($ez_search_model,'cadidate_active_state')->dropDownList($cadidate_active_state,['class' => 'form-control'])->label(false); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-10 col-sm-offset-2">
										 <?= Html::submitButton(' Find Resumes',["class"=>"btn btn-primary"]) ?>  
                                           
                                        </div>
                                    </div> 
									<?php ActiveForm::end(); ?>
                            </div>
                        </div>
                  </div>
                </div>
			</div>           
              	<?= Yii::$app->controller->renderPartial('@app/views/layouts/sidemenu_new') ?>
		</div>
	</div>
</section>
    
<!--********************************************************************************************** -->
<!--**************************************Main Content Ends Here************************************** -->
<!--********************************************************************************************** -->

 <?php   
    $result = array();	
	foreach($skills_designations_locations as $search_key => $search_val)
    	array_push($result, array("label"=>$search_key, "value"=> $search_val)); 

 ?>
  <script>
 
  $(function(){
	var	search_result = <?php echo json_encode(array_values($result));?> 
		var search_keyword = [];
		var availableTags2 = [];			
		$.each(search_result, function(i, v){		
		availableTags2.push({label:v.value, value: v.value, hiddenvalue: v.label});
		
		});
	function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( "#advancesearch-ez_search_field" )
      .bind( "keydown", function( event ) {
		   if (!this.value) {				
			  $("#search_keyword").val(null);
			}
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          response( $.ui.autocomplete.filter( availableTags2, extractLast( request.term ) ) );
        },
        focus: function() {
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
		 // console.log(terms);
		//  console.log('hidden value '+ui.item.hiddenvalue);
          // remove the current input
          terms.pop();
		  search_keyword.push(ui.item.hiddenvalue);
		  $("#search_keyword").val(search_keyword.join( "," ));
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( "," );
          return false;
        }
      });
	});	
$("#search_form").submit(function(){
	if($('#advancesearch-ez_search_field').val() == ''){
		$('#search_error').html('Search By field should not be blank !');
		return false;
	}
});	
  </script>

