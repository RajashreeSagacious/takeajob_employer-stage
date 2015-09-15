<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\ItSkills;
use app\models\JobSubCategory;
use app\models\TjDistrict;
use app\assets\AppAsset;

$this->title ='Ez Search - employer.takeajob.com';

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
	$search_designation['1-'.$v] = $v;

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
    <div class="profile-pages">
        <div class="container resume-fields-bg">
            <div class="custom_tabs_holder01">
               <ul class="nav nav-tabs">
                    <li><a href="<?php echo Url::to(['search/advanced_search']); ?>" >Advanced Search</a></li>                  
                    <li class="active"><a href="<?php echo Url::to(['search/ez_search']); ?>" >EZ Search</a></li>
                    <li><a href="<?php echo Url::to(['search/special_search']); ?>" >Special Search</a></li>                   
                </ul>
                <div class="tab-content">
                    <div id="ez_search" class="tab-pane active">
                        <div class="panel panel-default custom-panel">
						     <?php $form = ActiveForm::begin(['id'=>'search_form']);  ?>							 
                            <div class="panel-body">
                                <div class="form-group col-md-9">
									<?php echo $form->field($ez_search_model,'ez_search_field')->textInput(['class' => 'form-control','placeholder' => 'Search by Skills, Designation, Location, Salary and Experience'])->label('Search By: '); 
								echo '<div id="search_error" class="error"></div>';
								
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
								
								echo $form->field($ez_search_model,'cadidate_active_state')->dropDownList($cadidate_active_state,['class' => 'form-control'])->label('Candidates Active in Last:'); 
								
								echo  $form->field($ez_search_model,'search_keyword')->hiddenInput(['id' => 'search_keyword'])->label('Note: php, Software Engineer, Mumbai, 1-10 years/yrs, 1-15 lakhs/lks/lpa '); ?>
                                </div>
                                <div class="form-group col-md-3">
								    <?= Html::submitButton('Find Resumes',["class"=>"btn btn-default fa fa-search"]) ?>                                    
                                </div>                                
                            </div>
							<?php ActiveForm::end(); ?>
                        </div>
                        <div class="help-block with-errors">
                            * Searches all candidates active in last 1 year sorted by Relevance
                        </div>
                        <h3>EZ Tips</h3>
                        <div class="panel-group custom-accordian01 add-border-bottom" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <p class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-ez-keywords">
                                          <span class="glyphicon glyphicon-plus"></span>
                                          Keywords
                                        </a>
                                    </p>
                                </div>
                                <div id="collapse-ez-keywords" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="list-style01">
                                            <li>For all the words users can specify boolean operators
                                                <ul>
                                                    <li>Java & J2EE</li>
                                                </ul>
                                            </li>
                                            <li>For all the words users can specify boolean operators
                                                <ul>
                                                    <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quis tristique dolor. Ut sit amet velit sed diam cursus vehicula at at arcu. </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group custom-accordian01 add-border-bottom" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <p class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-ez-experience">
                                          <span class="glyphicon glyphicon-plus"></span>
                                          Experience
                                        </a>
                                    </p>
                                </div>
                                <div id="collapse-ez-experience" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="list-style01">
                                            <li>For all the words users can specify boolean operators
                                                <ul>
                                                    <li>Java & J2EE</li>
                                                </ul>
                                            </li>
                                            <li>For all the words users can specify boolean operators
                                                <ul>
                                                    <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quis tristique dolor. Ut sit amet velit sed diam cursus vehicula at at arcu. </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group custom-accordian01 add-border-bottom" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <p class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-ez-annual_salary">
                                          <span class="glyphicon glyphicon-plus"></span>
                                          Annual Salary
                                        </a>
                                    </p>
                                </div>
                                <div id="collapse-ez-annual_salary" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="list-style01">
                                            <li>For all the words users can specify boolean operators
                                                <ul>
                                                    <li>Java & J2EE</li>
                                                </ul>
                                            </li>
                                            <li>For all the words users can specify boolean operators
                                                <ul>
                                                    <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quis tristique dolor. Ut sit amet velit sed diam cursus vehicula at at arcu. </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-group custom-accordian01 add-border-bottom" id="accordion">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <p class="panel-title">
                                        <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapse-ez-locations">
                                          <span class="glyphicon glyphicon-plus"></span>
                                          Locations
                                        </a>
                                    </p>
                                </div>
                                <div id="collapse-ez-locations" class="panel-collapse collapse">
                                    <div class="panel-body">
                                        <ul class="list-style01">
                                            <li>For all the words users can specify boolean operators
                                                <ul>
                                                    <li>Java & J2EE</li>
                                                </ul>
                                            </li>
                                            <li>For all the words users can specify boolean operators
                                                <ul>
                                                    <li>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nunc quis tristique dolor. Ut sit amet velit sed diam cursus vehicula at at arcu. </li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div> 
    </div>
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

