<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
//use app\assets\AppAsset;
use app\assets\AppAsset;
use app\modules\search\models\AdvanceSearch;
use app\models\UsersOtpModel;
use app\models\EmployerOrDesignation;
use app\models\JobSubCategory;
use app\models\TjDistrict;
use app\models\UsersProfilePhoto;
use app\models\Experience;
use app\models\UserSnapshot;
use app\models\ItSkills;
use app\models\NoticePeriod;
use app\models\UserLoggedHistory;
use yii\helpers\ArrayHelper;
AppAsset::register($this);
$this->title ='Candidate Search Result - employer.takeajob.com';

//$this->registerCssFile(Yii::$app->request->baseUrl.'/css/style.min.css',['depends' => [\yii\web\JqueryAsset::className()]]);
//$this->registerCssFile(Yii::$app->request->baseUrl.'/css/style.scss',['depends' => [\yii\web\JqueryAsset::className()]]);
// $this->registerJsFile(Yii::$app->request->baseUrl.'/js/jquery.sumoselect.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/jquery-ui.js',['depends' => [\yii\web\JqueryAsset::className()]]);
echo '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">';

$search_result_model = new AdvanceSearch();
Yii::$app->session['latest_cids'] = array(349,341,326,323,347);
$latest_visited_cids = UsersOtpModel::find()->asArray()->where(array('in', 'tj_id', Yii::$app->session['latest_cids']))->all();
$frequently_visited_cids = UsersOtpModel::find()->asArray()->where(array('in', 'tj_id', Yii::$app->session['frequent_cids']))->all();
$frequently_visited_cids = UsersOtpModel::find()->asArray()->where(array('in', 'tj_id', Yii::$app->session['frequent_cids']))->all();

$districtdata =  TjDistrict::find()->orderBy('tj_dt_districtname ASC')->all();
$districtdata = ArrayHelper::map($districtdata,'tj_dt_districtId','tj_dt_districtname');

$location = [];

foreach($districtdata as $loc_key => $loc_val)
	array_push($location, array("label"=>$loc_key, "value"=> $loc_val)); 

 $skill_data = ItSkills::find()->select('tj_skill_names')->orderBy('tj_skill_names')->asArray()->all();
foreach($skill_data as $skills)
	$all_skills[] = $skills['tj_skill_names'];
$all_skills = array_values(array_intersect_key($all_skills, array_unique(array_map('strtolower', $all_skills))));


$JobsubData = JobSubCategory::find()->all();
$JobsubData  = ArrayHelper::map($JobsubData ,'tj_jsc_id' ,'tj_jsc_name');

foreach($JobsubData as $k => $v)	
	$search_designation['1-'.$k] = $v;

foreach($all_skills as $key => $val)	
	$search_skills['0-'.$val] = $val;

$skills_designations = array_merge($search_skills,$search_designation); 
 
$result = array();

foreach($skills_designations as $search_key => $search_val)
	array_push($result, array("label"=>$search_key, "value"=> $search_val)); 

?>
<style>
input[type="checkbox"] {
	display:block !important;
}
.no_details:hover, .no_details:focus {
    color: #337ab7;
    text-decoration: none;
}
</style>
  <link href='http://fonts.googleapis.com/css?family=Roboto:400,500,700' rel='stylesheet' type='text/css'>

<section class="tj-jobsearch">
  <div class="container">
    <div class="row">
      <div class="col-md-12 tj-jobsearch-design">
        <div class="col-md-12"><img src="../../images/search_images/job-search-map.jpg" class="img-responsive">
        </div>
        <div class="col-md-12">
          <div class="col-md-9 available-jobs" id = "recently-visited">
            <h4 class="register-text"><i class="fa fa-suitcase padding-right"></i>Available Candidates</h4>
			<?php
			   	$latest_cid_count = count($latest_visited_cids);
			   	$candidate_kills = "NA";
				$notice = "NA";	
				$designation = "NA";
				$company = "NA";
				if(!empty($latest_visited_cids)){
				foreach($latest_visited_cids as $latest_visited){
					 
			    $skills = ItSkills::find()->select("tj_skill_names")->where(["tj_it_skill_user_id" => $latest_visited['tj_id']])->All();
				if(!empty($skills)){
					foreach($skills as $key=>$skill){
						$skill_names[] = $skill['tj_skill_names'];
					}
					$candidate_kills = implode(", ",array_unique($skill_names));
				}
				$job_category = EmployerOrDesignation::find()->where(['tj_employer_user_id' => $latest_visited['tj_id']])->andWhere(['tj_employer_status_of_employer' => 1])->One();
				$logged_history = UserLoggedHistory::find()->select("tj_logged_in_at")->where(["tj_logger_user_id" => $latest_visited['tj_id']])->orderBy("tj_logger_id DESC")->One();
				//echo "<pre>";print_r($logged_history);exit;
				if(!empty($job_category)){
					
					$curr_designation = JobSubCategory::findOne($job_category['tj_employer_designation']); 							
					$designation = $curr_designation['tj_jsc_name'];
					$company = $job_category['tj_employer_name'];
					$notice = NoticePeriod::findOne($job_category['tj_employer_notice']);
					if(!empty($notice))
					$notice = $notice['tj_notice'];			
				}
				
			    $job_location = TjDistrict::findOne($latest_visited['tj_users_location']);		
				$image_location = Yii::$app->getUrlManager()->getBaseUrl().'/images/avatar.png';
				$user_profilephoto = UsersProfilePhoto::findOne(['tj_upp_user_id' => $latest_visited['tj_id']]); 
				
				if(isset($user_profilephoto))                                                                              
				$image_location = Yii::getAlias('@jobseeker').''.str_replace('@web','',$user_profilephoto->tj_upp_path_medium);
				  
				$experienceData = Experience::findOne($latest_visited['tj_users_experience']);
				$experience = $experienceData->tj_exp_years;
				  
				$user_snapshot = UserSnapshot::find()->select('tj_snap_annual_slary_lakhs,tj_snap_annual_salary_thousands')->where(['tj_snap_user_id' => $latest_visited['tj_id']])->One();
				 
				$salary = "NA";
				if($user_snapshot)
				$salary = (($user_snapshot -> tj_snap_annual_slary_lakhs != "" || $user_snapshot -> tj_snap_annual_salary_thousands != ""))?'<i class="fa fa-inr"></i>'.$user_snapshot -> tj_snap_annual_slary_lakhs.' - '.($user_snapshot -> tj_snap_annual_slary_lakhs+1).' LPA':"NA";
			?>
            <div class="col-md-12 jobs-page">
              <div class="col-md-2 company-logo">			  
                <img src="<?= $image_location; ?>" class="img-responsive img-circle">
              </div>
              <div class="col-md-10 company-details">
                <div class="col-md-12 pad-left">
                  <div class="col-md-7 pad-top-left"><h4><?php echo $latest_visited['tj_users_fname']." ".$latest_visited['tj_users_lname'] ?></h4></div>
                  <div class="col-md-5 pad-left">
                    <div class="date-more">
                      <div class="btn-group edit-btn pull-right" role="group" aria-label="...">
                        <a href="<?php echo Yii::$app->homeUrl; ?>search/search/view_candidate_profile?cid=<?php echo $latest_visited['tj_id']; ?>" target="_blank"><button type="button" class="btn btn-default edit-icon"><i class="fa fa-plus right-pad"></i>More</button></a>
						<!--button href="#" class="btn btn-gray fa toggle collapsed fa-plus" data-toggle="collapse" data-target="#job_itm_collapse_2"></button-->
						<!-- <button type="button"  id="contact_<?=$latest_visited['tj_id'] ?>" class="btn btn-default edit-icon"><i class="fa fa-star-o right-pad"></i>Contact</button> -->
                        <!--button type="button" class="btn btn-default edit-icon"><i class="fa fa-check-square-o right-pad"></i>Apply</button-->
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-10 company-about">
                  <p>
				  Current Company : <?= $company ?>
				  <br/>
				  Current Designation : <?= $designation ?>
				  <br/>
				  Notice Period : <?= $notice?>
				  <br/>
				  <br/>
				  Skills : <?= $candidate_kills ?>
				   </p>
                </div>
                <div class="col-md-12 pad-left">
	              <div class="col-md-3 work-loc"><i class="fa fa-map-marker"></i><?= $job_location->tj_dt_districtname;?></div>
	              <div class="col-md-3 work-exp"><i class="fa fa-graduation-cap"></i><?= isset($experience)?$experience:"NA"; ?></div>                 
	              <div class="col-md-3 work-sal"><i class="fa fa-money"></i> <?= $salary; ?></div><br><br>
				  <span id="more_<?=$latest_visited['tj_id']?>"  >
					  <div class="col-md-6 work-date"><i class="fa fa-envelope-o"></i> <?= $latest_visited['tj_users_email'] ?></div>
					  <div class="col-md-3 work-date"><i class="fa fa-mobile"></i> <?= $latest_visited['tj_users_phone'] ?></div>
					  <div class="col-md-3 work-date"><i class="fa fa-clock-o"></i> <?= date("F jS, Y",strtotime($logged_history['tj_logged_in_at'])) ?></div>
				  <span>
                </div>
              </div>
            </div> 
            <?php  
				}
			}			
			
			?>			

            <div class="div_more_details" >
				<?php 			
				$style_val = $style_more = "";
				if($latest_cid_count == 0 )  
					$style_more = "display:none;";                    
				else 
					$style_val = "display:none;";					   
				?>
				<a id="" href='javascript:void(0);' class='more_details no_details' style="<?php echo $style_val; ?>"><div class=\"full\"><h4 >Well, it looks like there are no result matching your criterias.</h4></div> 
				</a>
				<a id="" href="javascript:void(0);" class="more_details more_details_list" style="<?php echo $style_more; ?>"><span class="moreloader" id="latest_more" >More Jobs >></span> </a>
			<div class="loader job_loader" ><?php echo Html::img('@web/images/spinner.gif', [ 'alt' => 'google_map', "class" => "fa fa-spinner fa-spin" , "style" => "width: 75px;display:none"]); ?></div>
			</div>       

            
          </div>
		  <?php $form = ActiveForm::begin(['id'=>'advance_search']);  ?>	
		  
          <div class="col-md-3 filter-search ">
            <h4 class="register-text"><i class="fa fa-filter padding-right"></i>Filter Results</h4>
            <div class="col-md-12 filter-search-inner">
				<div class="filters">
	              <h5 class="sub-heading">Skills, Designations</h5>
	               <div class="search-desig">
					    <?php echo $form->field($search_result_model,'ez_search_field')->textInput(['class' => 'form-control','placeholder' => 'Search By Skills, Designations'])->label(''); 
						echo  $form->field($search_result_model,'search_keyword')->hiddenInput(['id' => 'search_keyword'])->label(''); ?>
	               </div>            
              	</div>            
            </div>
          	<div class="col-md-12 filter-search">
	            <div class="col-md-12 filter-search-inner">
	                <div class="filters">
		                <h5 class="sub-heading">Location</h5>
		                <div class="col-md-12 filter-options">
		                	<?php 
							echo $form->field($search_result_model,'all_location')->textInput(['class' => 'form-control','placeholder' => 'Search Location'])->label('');
							echo  $form->field($search_result_model,'search_location')->hiddenInput()->label('');?>
		                </div>
	                </div>
	            </div>
         	</div>
     
          <div class="col-md-12 filter-search">
            <div class="col-md-12 filter-search-inner">
              <div class="filters">
                <h5 class="sub-heading">Experience</h5>
                <div class="col-md-12 filter-options">
				
					<?php 
						$exp = [];
						for($i = 0;$i <= 40;$i++)
								$exp[$i] = $i;
						
					?>
					<div class="col-sm-6 f-exp-pad">
						<?= $form->field($search_result_model, 'experience_from', ['template'=>'{input}{error}'])->dropDownList($exp, ['prompt'=>'- From -']) ?>
					</div>
						<?php unset($exp[0]);?>
					 <div class="col-sm-6 f-exp-pad">
						<?= $form->field($search_result_model, 'experience_to', ['template'=>'{input}{error}'])->dropDownList($exp, ['prompt'=>'- To -']) ?>
                     </div>						
					<div id="exp_error" class="error"></div>
                  
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12 filter-search">
            <div class="col-md-12 filter-search-inner">
              <div class="filters">
                <h5 class="sub-heading">Salary</h5>
				   <div class="col-md-12 filter-options">				
				
					 <?php 
						$lakhs = [];
						for($i = 0;$i <= 50;$i++){
							 $lakhs[$i] = $i;
						}?>      
					  <div class="col-sm-6 f-exp-pad">
						<?= $form->field($search_result_model, 'annual_salary_lakhs_from', ['template'=>'{input}{error}'])->dropDownList($lakhs, ['prompt'=>'- Min -']) ?>						
					 </div>
					  <div class="col-sm-6 f-exp-pad">
						<?php unset($lakhs[0]); ?>
						<?= $form->field($search_result_model, 'annual_salary_lakhs_to', ['template'=>'{input}{error}'])->dropDownList($lakhs, ['prompt'=>'- Max -']) ?>					
					</div>
						<div id="sal_error" class="error"></div>				
				
                </div>
              </div>
            </div>
          </div>
          <div class="col-md-12 filter-search">
            <div class="col-md-12 filter-search-inner">
              <div class="filters">
                <h5 class="sub-heading">JOB TYPES</h5>
                <div class="col-md-12 filter-options">
				<input type="checkbox" value="4" class="job_type f-left" id="job_type1" name="job_type[]" ><span class="r-mar-right">All Types</span>
                </div>
                <div class="col-md-12 filter-options">
                  <input type="checkbox" value="0" class="job_type f-left" id="job_type4" name="job_type[]" ><span class="r-mar-right">Full Time</span>
                </div>
                <div class="col-md-12 filter-options">
                  <input type="checkbox" value="1" class="job_type f-left" id="job_type3" name="job_type[]"><span class="r-mar-right">Part Time</span>
                </div>
                <div class="col-md-12 filter-options">
                  <input type="checkbox" value="2" class="job_type f-left" id="job_type2" name="job_type[]" ><span class="r-mar-right">Freelance</span>
                </div>
                <div class="col-md-12 filter-options">
                  <input type="checkbox" value="3" class="job_type f-left" id="job_type5" name="job_type[]"><span class="r-mar-right">Contract</span>
                </div>
              </div>
            </div>
          </div>
        </div>
    <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>
</section>

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
          terms.pop();
		  search_keyword.push(ui.item.hiddenvalue);
		  $("#search_keyword").val(search_keyword.join( "," ));
		  showValues();
          terms.push( ui.item.value );          
          terms.push( "" );
          this.value = terms.join( "," );
          return false;
        }
      });
	});	

/// LOCATION SEARCH AUTOCOMPLETE
 
	$(function(){
	var	search_result = <?php echo json_encode(array_values($location));?> 
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
 
    $( "#advancesearch-all_location" )
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
          terms.pop();
		  search_keyword.push(ui.item.hiddenvalue);
		  $("#advancesearch-search_location").val(search_keyword.join( "," ));
		  showValues();
          terms.push( ui.item.value );          
          terms.push( "" );
          this.value = terms.join( "," );
          return false;
        }
      });
	});	


 function showValues() { 

    var str = $( "#advance_search" ).serialize();
    $( "#results" ).text( str );
		$.ajax({
			  type: "POST",
			  url: 'filter',
			  data: $( "#advance_search" ).serialize(),
			  success: function( response ) {
						 // console.log(response.frequent_cids);
						 console.log(response.latest_cids);
				
					     $("#recently-visited").html("");					    
						 if(response.latest_cids == "")
							  $("#recently-visited").html('<a id=""  class="more_details no_details" ><div class=\"full\"><h4 >Well, it looks like there is no search result matching your criteria.</h4></div></a>');
						 else{
							 $('#recently-visited').append('<h4 class="register-text"><i class="fa fa-suitcase padding-right"></i>Available Candidates</h4>');
						   $.each(response.latest_cids,function(lk,lv){ 						   
						   
						     $('#recently-visited').append('<div class="col-md-12 jobs-page"><div class="col-md-2 company-logo"><img src="'+lv.tj_upp_path_medium+'" class="img-responsive img-circle"></div><div class="col-md-10 company-details"><div class="col-md-12 pad-left"><div class="col-md-7 pad-top-left"><h5>'+lv.user_name+'</h5></div><div class="col-md-5 pad-left"><div class="date-more"><div class="btn-group edit-btn pull-right" role="group" aria-label="..."><button type="button" class="btn btn-default edit-icon"><i class="fa fa-plus right-pad"></i>More</button><button type="button" class="btn btn-default edit-icon"><i class="fa fa-star-o right-pad"></i>Save Job</button><button type="button" class="btn btn-default edit-icon"><i class="fa fa-check-square-o right-pad"></i>Apply</button></div></div></div></div><div class="col-md-10 company-about"><p>Current Company : company<br/>Skills : skills</p></div><div class="col-md-12 pad-left"><div class="col-md-3 work-date"><i class="fa fa-star"></i>'+lv.tj_jc_name+'</div><div class="col-md-3 work-loc"><i class="fa fa-map-marker"></i>'+lv.tj_dt_districtname+'</div><div class="col-md-3 work-exp"><i class="fa fa-life-ring"></i>'+lv.experience+'</div><div class="col-md-3 work-date"><i class="fa fa-calendar"></i>'+lv.salary+'</div></div></div></div>');
						  });
						   $('#recently-visited').append('<div class="div_more_details" ><a id="" href="javascript:void(0);" class="more_details more_details_list" ><span class="moreloader" id="latest_more" >More Jobs >></span> </a><div class="loader job_loader" ><?php echo Html::img('@web/images/spinner.gif', [ 'alt' => 'google_map', "class" => "fa fa-spinner fa-spin" , "style" => "width: 75px;display:none"]); ?></div></div>'); 
					  } 
				
			  }
		});	
     }			
  $( document ).on( "change", "input[type='checkbox'], select", showValues );
  $("#latest_more").on("click",showValues);
  $("#frequent_more").on("click",showValues);
  $( "#contact" ).click(function() {
  alert( "Handler for .click() called." );
});
/* $("#contact").on("click",function(){
	alert("dssdf");
	//$("#more").toggle();
}); */  
  </script>

