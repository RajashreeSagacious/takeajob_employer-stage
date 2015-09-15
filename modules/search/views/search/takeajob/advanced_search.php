<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\TjDistrict;
use app\models\TjIndustry;
use app\models\TjJobCategory;
use app\models\TjCountries;
use app\models\NoticePeriod;
use app\models\Education;
use app\models\Experience;
use app\models\Skills;
use app\models\Company;
use app\models\University;
use app\models\JobSubCategory;
use app\models\UsersProjectsRole;
use app\assets\AppAsset;
AppAsset::register($this);

	$this->title ='Advance Search - employer.takeajob.com';
	$this->registerCssFile(Yii::$app->request->baseUrl.'/css/jquery-ui.css',['depends' => [\yii\web\JqueryAsset::className()]]);
	$this->registerJsFile(Yii::$app->request->baseUrl.'/js/jquery-ui.js',['depends' => [\yii\web\JqueryAsset::className()]]);
	$this->registerJsFile(Yii::$app->request->baseUrl.'/js/select2.min.js',['depends' => [\yii\web\JqueryAsset::className()]]);
	$this->registerCssFile(Yii::$app->request->baseUrl.'/css/sumoSelect.css',['depends' => [\yii\web\JqueryAsset::className()]]);
	$this->registerJsFile(Yii::$app->request->baseUrl.'/js/jquery.sumoselect.js',['depends' => [\yii\web\JqueryAsset::className()]]); 


	
	
	$Companies = Company::find()->select('tj_cm_id,tj_cm_company_name')->asArray()->all();
    $companies_data = ArrayHelper::map($Companies,'tj_cm_id','tj_cm_company_name');
	
	$districtName = TjDistrict::find()->orderBy('tj_dt_districtname')->all();
	$location_names = ArrayHelper::map($districtName,'tj_dt_districtId','tj_dt_districtname');
	
	$university = University::find()->where(['tj_uni_status' => 1])->orderBy('tj_uni_name')->all();
	$university_names = ArrayHelper::map($university,'tj_uni','tj_uni_name'); 
	
	$experience = Experience::find()->all();
    $exp_data = ArrayHelper::map($experience,'tj_exp_id','tj_exp_years');	
	
	$skill = Skills::find()->all();      
    $skill_data = ArrayHelper::map($skill,'tj_sk_id','tj_sk_name'); 	
	
	$desinations =  JobSubCategory::find()->orderBy('tj_jsc_name')->all();
	$desination_role = ArrayHelper::map($desinations,'tj_jsc_id','tj_jsc_name');
	$desination_role = array_unique($desination_role);
	
	
	$IndustryData = TjIndustry::find()->all();                                  
	$industry_type = ArrayHelper::map($IndustryData,'tj_ind_id','tj_ind_name');

	$JobCategoryData = TjJobCategory::find()->all();
	$functional_area = ArrayHelper::map($JobCategoryData,'tj_jc_id','tj_jc_name');
	
	$countries = TjCountries::find()->all();
	$allcountries = ArrayHelper::map($countries,'tj_countryId','tj_countryname'); 
	 
	$Notice_periodData = NoticePeriod::find()->all();
	$noticeperiod = ArrayHelper::map($Notice_periodData,'tj_notice_id','tj_notice');
	
	$fromdb = Education::find()->where('tj_edu_category = 1')->orderBy('tj_edu_title ASC')->all();
	$graduation_courses = ArrayHelper::map($fromdb, 'tj_edu_id', 'tj_edu_title');
	
	$specialization = [];
	
	$fromdb = Education::find()->where('tj_edu_category = 2')->orderBy('tj_edu_title ASC')->all();
	$post_graduation_courses = ArrayHelper::map($fromdb, 'tj_edu_id', 'tj_edu_title');
	
	$fromdb = Education::find()->where('tj_edu_category = 3')->orderBy('tj_edu_title ASC')->all();
	$doctorate_courses = ArrayHelper::map($fromdb, 'tj_edu_id', 'tj_edu_title');
	
	/* $all_categories_suggestion = [];
	$all_categories_suggestion = array_merge($skill_data,$role,$desination_data); */
	
	
?>
<style>
.error{
	text-align:center;
	color:#a94442;
}
</style>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    <div class="profile-pages">
        <div class="container resume-fields-bg">
            <div class="col-md-9">
            	<div class="custom_tabs_holder01">
	                <ul class="nav nav-tabs">
	                    <li class="active"><a href="<?php echo Url::to(['search/advanced_search']); ?>" >Advanced Search</a></li>                  
	                    <li><a href="<?php echo Url::to(['search/easy_search']); ?>">Easy Search</a></li>
	                    <li><a href="<?php echo Url::to(['search/special_search']); ?>" >Special Search</a></li>                   
	                    <li><a href="<?php echo Url::to(['search/role_search']); ?>" >Role Search</a></li>                   
	                </ul>
	                <div class="tab-content">
	<!--********************************************************************************************** -->
	<!--**************************************Advance Search Tab Starts Here************************************** -->
	<!--********************************************************************************************** -->
	                    <div id="advanced_search" class="tab-pane fade in active">
	                       
	                        <?php  
								$form = ActiveForm::begin([
									'id'=>'search_form',
									'enableClientValidation' => true,
									
									//'method' => 'post',
									//'action' => ['search/search_result'],
									//'options' => ['class' => 'form-horizontal' ,"role"=>"form"],
									'fieldConfig' => [
									'template' => "
									{label}\n <div class=\"col-sm-9\">{input}{error}</div>",
									'labelOptions' => ['class' => 'control-label col-sm-3'],
											  'errorOptions' => ['class' => 'help-block help-block-error '],
											  'inputOptions' => ['class' => 'form-control'],
								],
								]);
	                         ?>
	                            <h4>Basic Details</h4>
	                            <div class="custom_tabs_holder02">                               
	                                <div class="tab-content">
	                                    <div id="Booleansearch" class="tab-pane fade">                                        
	                                        <div class="form-group">
	                                            <label class="control-label col-md-3" for="email"><span class="redtxt">*</span> Keywords:</label>
	                                            <div class="col-md-9 form-inline"> 
												  <?= $form->field($search_model,'boolean_value')->textInput()->label(''); ?>
												  <?= $form->field($search_model,'boolean_search_type')->dropDownList(array('bool' => 'Boolean','all' => ' All words','any' => 'Any of the words','exact' => 'Exact Phrase'),['placeholder'=>'Select Location'])->label(''); ?>
	                                            </div>
	                                        </div> 
	                                    <div class="clear"></div>
	                                    </div>
										<div class="block-a">
	                                    <div id="keywordssearch" class="tab-pane fade in active">
											<?= $form->field($search_model,'search_skills')->textInput(['class' => 'multiple_select form-control'])->label('Search by Skills:'); ?>
											<?php /*<?= $form->field($search_model,'all_of_keywords')->textInput(['class' => 'multiple_select'])->label('All the Keywords:'); ?>
											<?= $form->field($search_model,'excluding_of_keywords')->textInput(['class' => 'multiple_select'])->label('Excluding Keywords:'); ?>       */ ?>                                
	                                    </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="block-a">
	                                <div class="form-group">
	    							<?php $search_model->search_in = 0;?>
	    							<?= $form->field($search_model, 'search_in')->radioList(array('0'=>'Resume Title','1'=>'Entire Resume'),['class' => 'search_in'])->label('Search In:'); ?> 
										
									</div>
	                                <div class="form-group">
									 <label class="control-label col-md-3" for="text"><span class="redtxt">*</span> Experience:</label>
	                                    <div class="col-md-9 form-inline"> 
										
										<?php									
											/* $years = [];
											for($i = 0;$i <= 30;$i++){
												 $years[] = $i;
											} 
										
											<?= $form->field($search_model, 'experience_from', ['template'=>'{input}{error}'])->dropDownList($exp_data, ['prompt'=>'- Years -']) ?>
											<div class="form-group" style="width: 60px;margin-left: 34px;">to</div>
											<?php array_shift($exp_data); ?>
											<?= $form->field($search_model, 'experience_to', ['template'=>'{input}{error}'])->dropDownList($exp_data, ['prompt'=>'- Years -']) ?>
											*/?>
											<?php 
											$exp = [];
											for($i = 0;$i <= 40;$i++){
												 $exp[$i] = $i;
											}?>
											<?= $form->field($search_model, 'experience_from', ['template'=>'{input}{error}'])->dropDownList($exp, ['prompt'=>'- From -']) ?>
											<div class="form-group" style="">to</div>
											<?php unset($exp[0]);
											//$exp_to = array_slice($exp, 0, 1);
													?>
											<?= $form->field($search_model, 'experience_to', ['template'=>'{input}{error}'])->dropDownList($exp, ['prompt'=>'- To -']) ?>										
											<div id="exp_error" class="error"></div>										
	                                    </div>
									
	                                </div>
	                                <div class="form-group">
	                                    <label class="control-label col-md-3" for="text"><span class="redtxt">*</span> Annual Salary:</label>
	                                    <div class="col-md-9 form-inline">                                           
	                                         <?php 
											$lakhs = [];
											for($i = 0;$i <= 50;$i++){
												 $lakhs[$i] = $i;
											}?>      
										
											<?= $form->field($search_model, 'annual_salary_lakhs_from', ['template'=>'{input}{error}'])->dropDownList($lakhs, ['prompt'=>'- From -']) ?>
											    
											<div class="form-group" style="">to</div>
											<?php unset($lakhs[0]); ?>
											<?= $form->field($search_model, 'annual_salary_lakhs_to', ['template'=>'{input}{error}'])->dropDownList($lakhs, ['prompt'=>'- To -']) ?> 	
											
											<div id="sal_error" class="error"></div>                                      
	                                    </div>
	                                </div>
	                                <div class="form-group"> 
									  <?= $form->field($search_model,'tj_users_location')->dropDownList($location_names,['placeholder'=>'Select Location','class' => 'SelectBox form-control','multiple' => 'true'])->label('Candidate current Location:'); ?>	
	                                  
	                                </div>
	                             
	                                <div class="form-group">
									    <?php
										$search_model->current_or_preferred = 0;
										  echo $form->field($search_model, 'current_or_preferred')->radioList(array('0'=>'And','1'=>'Or'),['class' => 'current_or_preferred'])->label(''); ?> 
										  <?= $form->field($search_model,'tj_snap_prefered_location')->dropDownList($location_names,['placeholder'=>'Select Preferred Location','class' => 'SelectBox form-control','multiple' => 'true'])->label('Candidate current Location:'); ?>									  
	                              
	                                </div>
	                                <div class="clear"></div>
	                            </div>
	                            <div class="block-a">
	                                <h4>Employment Details</h4>
	                                <div class="form-group">								
	                                    <?= $form->field($search_model,'functional_area')->dropDownList($functional_area,['placeholder'=>'Select Functional Area', 'class' => 'SelectBox form-control','multiple' => 'multiple'])->label('Functional Area:'); ?>
										
										 <?= $form->field($search_model,'role')->dropDownList($desination_role,['placeholder'=>'Select role', 'class' => 'SelectBox form-control','multiple' => 'multiple'])->label('Role:'); ?>
										
	                                    <?= $form->field($search_model,'industry_type')->dropDownList($industry_type,['placeholder'=>'Select Industry', 'class' => 'SelectBox form-control','multiple' => 'multiple'])->label('Industry Type:'); ?>
		                                
	                                    <label class="col-md-3 control-label">Employers:</label>
	                                    <div class="col-md-9 form-inline">
												<?= $form->field($search_model,'employers')->textInput(['class' => 'form-control multiple_companies'])->label(''); ?>
												<?= $form->field($search_model,'emp_type')->dropDownList(array('1'=>'Current Employer','2'=>'Previous Employer','3'=>'Current / Previous'),['class' => 'form-control'])->label(''); ?>
	                                    </div>
	                                </div>                              
	                                <div class="form-group">
	                                    <label class="col-md-3 control-label">Designation:</label>
	                                    <div class="col-md-9 form-inline">
	                                           <?= $form->field($search_model,'search_keyword')->textInput(['id' => 'multiple_designation'])->label('');
												echo  $form->field($search_model,'designation')->hiddenInput(['id' => 'search_keyword'])->label('');
												?>
											   
												<?= $form->field($search_model,'designation_emp_type')->dropDownList(array('1'=>'Current Employer','2'=>'Previous Employer','3'=>'Current / Previous'),['class' => 'form-control'])->label(''); ?>	                                           
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="block-a">
	                                <h4>Educational Details</h4>
	                                <div class="form-group">                                 
											<?= $form->field($search_model, 'graduation_courses')->dropDownList($graduation_courses, ['placeholder'=>'Select Graduation Qualification','class' => 'SelectBox form-control','multiple' => 'true'])->label('UG Qualification:'); ?>										
											<?= $form->field($search_model, 'graduation_specialization')->dropDownList($specialization, ['placeholder'=>'Select Specialization','multiple' => 'true']) ?> 
	                                </div>
									
	                                <div class="form-group">                                                                     
										  
										   <?= $form->field($search_model,'ug_institute_name')->dropDownList($university_names,['placeholder'=>'Select Institute','class' => 'SelectBox form-control','multiple' => 'true'])->label('Institute Name:'); ?>
	                                </div>
	                                <div class="form-group">
	                                    <label class="col-md-3 control-label">Year of Passing:</label>
	                                    <div class="col-md-9 form-inline">
	                                            <div class="form-group">Between</div>
	                                            <?php									
												$years = [];
												$y = date('Y')+3;
												for($i = 0;$i <= 40;$i++){
													 $years[$y] = $y;
													 $y--;
												}
												
												//$years = array_reverse($years);?>
											
												<?= $form->field($search_model, 'ug_education_experience_from', ['template'=>'{input}{error}'])->dropDownList($years, ['prompt'=>'- From -','style' => ' ']) ?>
												<div class="form-group" style=" ">and</div>
												<?= $form->field($search_model, 'ug_education_experience_to', ['template'=>'{input}{error}'])->dropDownList($years, ['prompt'=>'- To -']) ?> 
												<div id="ug_error" class="error"></div>                                      
	                                    </div> 
	                                    <?php 
										$search_model -> ug_education_details = 0;
										echo $form->field($search_model, 'ug_education_details')->radioList(array('0'=>'And','1'=>'Or'),['class' => 'current_or_preferred'])->label(''); ?>
								   
								      <?= $form->field($search_model, 'post_graduation')->dropDownList($post_graduation_courses, ['placeholder'=>'Select Post Graduation Qualification','class' => 'SelectBox form-control','multiple' => 'true'])->label('PG Qualification:'); ?>										
									  <?= $form->field($search_model, 'post_graduation_specialization')->dropDownList($specialization, ['placeholder'=>'Select Specialization','multiple' => 'true']) ?> 
	                                   
	                                </div>
	                                <div class="form-group">
									
											<?= $form->field($search_model,'pg_institute_name')->dropDownList($university_names,['placeholder'=>'Select Institute','class' => 'SelectBox form-control','multiple' => 'true'])->label('Institute Name:'); ?>												
										
	                                </div>
	                                <div class="form-group">
	                                    <label class="col-md-3 control-label">Year of Passing:</label>
	                                    <div class="col-md-9">
	                                        <div class="form-inline">
	                                          <div class="form-group">Between</div>
	                                           
												<?= $form->field($search_model, 'pg_education_experience_from', ['template'=>'{input}{error}'])->dropDownList($years, ['prompt'=>'- From -','style' => ' ']) ?>
												<div class="form-group" style=" ">and</div>
												<?= $form->field($search_model, 'pg_education_experience_to', ['template'=>'{input}{error}'])->dropDownList($years, ['prompt'=>'- To -']) ?>
												<div id="pg_error" class="error"></div>                                      
	                                        </div>
	                                    </div> 
	                                </div>
	                                <div class="panel-group custom-accordian01" id="accordion">
	                                    <div class="panel panel-default">
	                                        <div class="panel-heading">										    
	                                            <p class="panel-title">
	                                                <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
	                                                  <span class="glyphicon glyphicon-plus"></span>
	                                                  Show Ph.D / Doctorate Qualification
	                                                </a>
	                                            </p>
	                                        </div>
	                                        <div id="collapseThree" class="panel-collapse collapse">
	                                            <div class="panel-body">
	                                                <div class="form-group">
													    <?php 
														$search_model->pg_education_details = 0;
														echo $form->field($search_model, 'pg_education_details')->radioList(array('0'=>'And','1'=>'Or'),['class' => 'current_or_preferred'])->label(''); ?>
														
														 <?= $form->field($search_model, 'doctorate')->dropDownList($doctorate_courses, ['placeholder'=>'Select Doctorate Qualification','class' => 'SelectBox form-control','multiple' => 'true'])->label('Doctorate Qualification:'); ?>										
														<?= $form->field($search_model, 'doctorate_specialization')->dropDownList($specialization, ['placeholder'=>'Select Specialization','multiple' => 'true']) ?> 
														
														                                                    
	                                                </div>
	                                                <div class="form-group">                                                   
	                                                        <?= $form->field($search_model,'doctorate_institute_name')->dropDownList($university_names,['placeholder'=>'Select Institute','class' => 'SelectBox form-control','multiple' => 'true'])->label('Institute Name:'); ?>
															
	                                                </div>
	                                                <div class="form-group">
	                                                    <label class="col-md-3 control-label">Year of Passing:</label>
	                                                    <div class="col-md-9 form-inline">
	                                                           <div class="form-group">Between</div>														
															
																<?= $form->field($search_model, 'doctorate_education_experience_from', ['template'=>'{input}{error}'])->dropDownList($years, ['prompt'=>'- From -','style' => '']) ?>
																<div class="form-group" style="">and</div>
																<?= $form->field($search_model, 'doctorate_education_experience_to', ['template'=>'{input}{error}'])->dropDownList($years, ['prompt'=>'- To -']) ?>
																<div id="dc_error" class="error"></div>                                      
	                                                    </div> 
	                                                </div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                            <div class="block-a">
	                                <h4>Affirmative Action</h4>
	                                <div class="form-group">
									   <?php
									      
										  ksort($noticeperiod);
										 
									   ?>
										<?= $form->field($search_model,'notice_period')->dropDownList($noticeperiod,['placeholder'=>'Select Notice Period', 'class' => 'SelectBox form-control','multiple' => true])->label('Notice Period:'); ?> 
									    <?php $category = array('0'=>'General','1' => 'SC','2' => 'ST','3' => 'OBC - Creamy','4' => 'None');?>
									    <?= $form->field($search_model,'category')->dropDownList($category,['placeholder'=>'Select Category','class' => 'SelectBox form-control','multiple' => 'true'])->label('Category:'); ?>									
	                                </div>								  
	                            </div>
	                       
	                            <div class="block-a">
	                                <h4>Display Options</h4>
									<?php 
									
									$search_model->cadidate_active_state = 180;
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
									?>
									 <?= $form->field($search_model,'cadidate_active_state')->dropDownList($cadidate_active_state,['class' => 'form-control'])->label('Candidates Active in Last:'); ?>
									
	                                <div class="form-group">
									<?php 
									  
									  $search_model->show_candidate_seeking = '0,1';
									  
									?>						
									<?= $form->field($search_model, 'show_candidate_seeking')->radioList(array('0'=>'Permanent Job','1'=>'Temporary/Contract Job','0,1' => 'Any'),['class' => 'show'])->label('Show Candidate Seeking:'); ?>
										<div class="col-md-9">	<?php echo $form->field($search_model, 'show_candidate[]')->checkboxList(['0' => 'Full Time Job', '1' => 'Part Time Job', '2' => 'Freelance', '3' => 'Contract']); ?>
										</div>
	                                </div>
	                                <div class="form-group">
	                                    <div class="col-md-9 col-md-offset-3">
										 <?= Html::submitButton('Find Resumes',["class"=>"btn btn-default"]) ?>
	                                    </div>
										
										
	                                </div>
	                            </div>
	                       
							<?php ActiveForm::end(); ?> 
	                    </div>
						
	<!--********************************************************************************************** -->
	<!--**************************************Advance Search Tab Ends Here************************************** -->
	<!--********************************************************************************************** -->
	                    
	                </div>
	            </div>
            </div>
           	<?= Yii::$app->controller->renderPartial('@app/views/layouts/sidemenu_new') ?>
        </div> 
    </div>
	
	<?php 
	 $des_result = array();			
	foreach($desination_role as $des_key => $des_val)
    	array_push($des_result, array("label"=>$des_key, "value"=> $des_val)); 
	
	?>
	
<script>
  $(function() {
    var availableTags = <?php echo json_encode(array_values($skill_data)); ?>;
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( ".multiple_select" )
      // don't navigate away from the field on tab when selecting an item
      .bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      })
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          // delegate back to autocomplete, but extract the last term
          response( $.ui.autocomplete.filter(
            availableTags, extractLast( request.term ) ) );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( "," );
          return false;
        }
      });
	});

	
	  
// DESIGNATION MULTIPLE AUTOCOMPLETE
 $(function() {  
	 var search_result = <?php echo json_encode(array_values($des_result)); ?>;
	 var search_keyword = [];
	 var availableTags = [];
	 $.each(search_result, function(i, v){		
		availableTags.push({label:v.value, value: v.value, hiddenvalue: v.label});
		
		});
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( "#multiple_designation" )
      // don't navigate away from the field on tab when selecting an item
       .bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      }) 
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          // delegate back to autocomplete, but extract the last term
          response( $.ui.autocomplete.filter(
            availableTags, extractLast( request.term ) ) );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
		  search_keyword.push(ui.item.hiddenvalue);
		  $("#search_keyword").val(search_keyword.join( "," ));
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( "," );
          return false;
        }
      });
  });
  
  // COMPANY NAMES AUTOCOMPLETE
  
  $(function() {  
	 var availableTags = <?php echo json_encode(array_values($companies_data)); ?>;
	  
    function split( val ) {
      return val.split( /,\s*/ );
    }
    function extractLast( term ) {
      return split( term ).pop();
    }
 
    $( ".multiple_companies" )
      // don't navigate away from the field on tab when selecting an item
       .bind( "keydown", function( event ) {
        if ( event.keyCode === $.ui.keyCode.TAB &&
            $( this ).autocomplete( "instance" ).menu.active ) {
          event.preventDefault();
        }
      }) 
      .autocomplete({
        minLength: 0,
        source: function( request, response ) {
          // delegate back to autocomplete, but extract the last term
          response( $.ui.autocomplete.filter(
            availableTags, extractLast( request.term ) ) );
        },
        focus: function() {
          // prevent value inserted on focus
          return false;
        },
        select: function( event, ui ) {
          var terms = split( this.value );
			
          // remove the current input
          terms.pop();
          // add the selected item
          terms.push( ui.item.value );
          // add placeholder to get the comma-and-space at the end
          terms.push( "" );
          this.value = terms.join( "," );
          return false;
        }
      });
  });
   
  </script>


   <?php $this->registerJs("
		
		var course_ids = [];
		$('#advancesearch-graduation_courses').on('change', function() {
		course_ids = [];
		course_ids.push($(this).val());		
		course_ids = course_ids.join(',');
		
	 	$.get('".\yii\helpers\Url::to(['/search/search/getspecialization'])."',{course_id:course_ids}, function(d){			
			 $('#advancesearch-graduation_specialization').html('');
			tmp = document.getElementById('advancesearch-graduation_specialization');
			tmp.options[tmp.options.length] = new Option('Select Specialization', '');
			$.each(d, function (i, v){
				tmp = document.getElementById('advancesearch-graduation_specialization');			
				tmp.options[tmp.options.length] = new Option(v,i);
				 //$('#advancesearch-graduation_specialization').addClass('SelectBox form-control');
				}); 
			  });				
		});	
		
		
		
		
		 $('#advancesearch-post_graduation').on('change', function() {
		course_ids = [];
		course_ids.push($(this).val());		
		course_ids = course_ids.join(',');
		
	 	$.get('".\yii\helpers\Url::to(['/search/search/getspecialization'])."',{course_id:course_ids}, function(d){			
			 $('#advancesearch-post_graduation_specialization').html('');
			tmp = document.getElementById('advancesearch-post_graduation_specialization');
			tmp.options[tmp.options.length] = new Option('Select Specialization', '');
			$.each(d, function (i, v){
				tmp = document.getElementById('advancesearch-post_graduation_specialization');			
				tmp.options[tmp.options.length] = new Option(v,i);
				 //$('#advancesearch-post_graduation_specialization').addClass('SelectBox form-control');
				}); 
			  });				
		});
		
		
		$('#advancesearch-doctorate').on('change', function() {
		course_ids = [];
		course_ids.push($(this).val());		
		course_ids = course_ids.join(',');
		
	 	$.get('".\yii\helpers\Url::to(['/search/search/getspecialization'])."',{course_id:course_ids}, function(d){			
			 $('#advancesearch-doctorate_specialization').html('');
			tmp = document.getElementById('advancesearch-doctorate_specialization');
			tmp.options[tmp.options.length] = new Option('Select Specialization', '');
			$.each(d, function (i, v){
				tmp = document.getElementById('advancesearch-doctorate_specialization');			
				tmp.options[tmp.options.length] = new Option(v,i);
				 //$('#advancesearch-doctorate_specialization').addClass('SelectBox form-control');
				}); 
			  });				
		}); 
	
"); 


		
	
	
	
	
$this->registerJs("
	
		$('#search_form').submit(function(){
			
		    if($('#advancesearch-experience_from option:selected').val() != ''){				
			 if(($('#advancesearch-experience_to option:selected').val() == '')|| ($('#advancesearch-experience_from option:selected').val() > $('#advancesearch-experience_to option:selected').val())){
				$('#exp_error').html('Please enter proper Experience range !');
				 return false; 
			   }
			}
			
			if($('#advancesearch-annual_salary_lakhs_from option:selected').val() != ''){			
			  if(($('#advancesearch-annual_salary_lakhs_to option:selected').val() == '')|| ($('#advancesearch-annual_salary_lakhs_from option:selected').val() > $('#advancesearch-annual_salary_lakhs_to option:selected').val())){				 
				$('#sal_error').html('Please enter proper Salary range !');
				 return false; 
			   } 
			}
			
			if($('#advancesearch-ug_education_experience_from option:selected').val() != ''){			
			  if(($('#advancesearch-ug_education_experience_to option:selected').val() == '')|| ($('#advancesearch-ug_education_experience_from option:selected').val() > $('#advancesearch-ug_education_experience_to option:selected').val())){				 
				$('#ug_error').html('Please enter proper ug year of passing range !');
				 return false; 
			   } 
			}
			
			if($('#advancesearch-pg_education_experience_from option:selected').val() != ''){
			   if(($('#advancesearch-pg_education_experience_to option:selected').val() == '')|| ($('#advancesearch-pg_education_experience_from option:selected').val() > $('#advancesearch-pg_education_experience_to option:selected').val())){				 
				$('#pg_error').html('Please enter proper pg year of passing range !');
				 return false; 
			   }  
			}
			
			if($('#advancesearch-doctorate_education_experience_from option:selected').val() != ''){			
			  if(($('#advancesearch-doctorate_education_experience_to option:selected').val() == '')|| ($('#advancesearch-doctorate_education_experience_from option:selected').val() > $('#advancesearch-doctorate_education_experience_to option:selected').val())){				 				
				$('#dc_error').html('Please enter proper Doctorate year of passing range !');
				 return false; 
			   } 
			} 
			
						
		}); 
");

$this->registerJs("

    window.asd = $('.SelectBox').SumoSelect({ csvDispCount: 4 });	
	 
	$(document).ready(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square',
                radioClass: 'iradio_square',
                increaseArea: '20%' // optional
            });
        });
  
        $(document).ready(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%' // optional
            });
        });
");

?>
  
  