<?php 
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\TjDistrict;
use app\models\EmpCities;
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

$this->title ='Advanced Search - employer.takeajob.com';
 

$this->registerCssFile(Yii::$app->request->baseUrl.'/css/jquery-ui.css',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/jquery-ui.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/select2.min.js',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerCssFile(Yii::$app->request->baseUrl.'/css/sumoSelect.css',['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJsFile(Yii::$app->request->baseUrl.'/js/jquery.sumoselect.js',['depends' => [\yii\web\JqueryAsset::className()]]);  

echo '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">';

    $Companies = Company::find()->select('tj_cm_id,tj_cm_company_name')->asArray()->all();
    $companies_data = ArrayHelper::map($Companies,'tj_cm_id','tj_cm_company_name');
	
	/*$districtName = TjDistrict::find()->orderBy('tj_dt_districtname')->all();
	$location_names = ArrayHelper::map($districtName,'tj_dt_districtId','tj_dt_districtname');*/
	
    $cityName = EmpCities::find()->orderBy('tj_city_name')->all();
    $location_names = ArrayHelper::map($cityName,'tj_cityId','tj_city_name');

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

?>
<section class="tj-advanced-search">
	<div class="container">
		<div class="row">
			<div class="col-md-9 tj-advanced-search-design">
                <div class="search-resume-tab-holder">
                     <ul class="nav nav-tabs">
	                    <li class="active"><a href="<?php echo Url::to(['search/advanced_search']); ?>" >Advanced Search</a></li>                 
	                    <li><a href="<?php echo Url::to(['search/easy_search']); ?>">Easy Search</a></li>
	                    <li><a href="<?php echo Url::to(['search/special_search']); ?>" >Special Search</a></li>                   
	                    <li><a href="<?php echo Url::to(['search/role_search']); ?>" >Role Search</a></li>                   
	                </ul>
                    <div class="tab-content">
                        <div id="advanced-search" class="tab-pane fade in active">
						 
                            <h4 class="advanced-search-text"><i class="fa fa-search-plus padding-right"></i>Advanced Search</h4>
                            <!-- <p class="text-caps">Create your Account Details</p> -->
                            <div class="tj-advanced-search-input">
                                <h4 class="tj-advanced-search-head">Basic Details</h4>
                               <?php  
								$form = ActiveForm::begin([
									//'class'=>'form-horizontal',
									'id'=>'search_form',
									'enableClientValidation' => true,
									
									//'method' => 'post',
									//'action' => ['search/search_result'],
									'options' => ['class' => 'form-horizontal' ,"role"=>"form"],
									'fieldConfig' => [
									'template' => "
									{label}\n <div class=\"col-sm-10\">{input}{error}</div>",
									'labelOptions' => ['class' => 'control-label col-sm-2'],
											  'errorOptions' => ['class' => 'help-block help-block-error'],
											  'inputOptions' => ['class' => 'form-control'],
								],
								]);
	                         ?>
                                   
									   
                                       <?= $form->field($search_model,'search_skills')->textInput(['class' => 'multiple_select form-control'])->label('Search by Skills:'); ?>
                                   
                                                                       
                                    <div class="form-group">
                                        <label class="control-label col-sm-2 vi" for="">Experience</label>
										<?php 
											$exp = [];
											for($i = 0;$i <= 40;$i++){
												 $exp[$i] = $i;
											}?>
                                        <div class="col-sm-5">
                                        <?=Html::activeDropDownList($search_model, 'experience_from', $exp, ['prompt'=>'- From -','class'=>'form-control'])?>
                                        <?=Html::error($search_model, 'experience_from',  ['class'=>'help-block help-block-error'])?>
                                           <?php //$form->field($search_model, 'experience_from')->dropDownList($exp, ['prompt'=>'- From -','class'=>'form-control'])->label(false); ?>
                                        </div>
                                        <div class="col-sm-5">
                                            <?php unset($exp[0]);
											echo Html::activeDropDownList($search_model, 'experience_to', $exp, ['prompt'=>'- To -','class'=>'form-control']);
                                            echo Html::error($search_model, 'experience_to',  ['class'=>'help-block help-block-error']);
											//echo $form->field($search_model, 'experience_to')->dropDownList($exp, ['prompt'=>'- To -','class'=>'form-control'])->label(false); ?>
												<div id="exp_error" class="error"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Annual Salary</label>
                                        <div class="col-sm-5">
                                           <?php 
                                            $lakhs = [];
                                            for($i = 0;$i <= 50;$i++){
                                                 $lakhs[$i] = $i;
                                            }?>      
                                        
                                            <?=Html::activeDropDownList($search_model, 'annual_salary_lakhs_from', $lakhs, ['prompt'=>'- From -','class'=>'form-control'])?>
                                            <?=Html::error($search_model, 'annual_salary_lakhs_from',  ['class'=>'help-block help-block-error'])?>
                                            </div>
                                        <div class="col-sm-5">
                                            <?php unset($lakhs[0]); ?>
                                            <?=Html::activeDropDownList($search_model, 'annual_salary_lakhs_to', $lakhs, ['prompt'=>'- To -','class'=>'form-control'])?>
                                            <?=Html::error($search_model, 'annual_salary_lakhs_to',  ['class'=>'help-block help-block-error'])?>
											<div id="sal_error" class="error"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Candidate current Location</label>
                                        <div class="col-sm-10">
                                           <?=Html::activeDropDownList($search_model, 'tj_users_location', $location_names, ['prompt'=>'- Select Current Location -','class' => 'SelectBox form-control','multiple' => 'true'])?>
                                            <?=Html::error($search_model, 'annual_salary_lakhs_from',  ['class'=>'help-block help-block-error'])?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <!-- <label class="radio-inline"><input type="radio" name="optradio">And</label>
                                            <label class="radio-inline"><input type="radio" name="optradio">Or</label> -->
                                            <?php
                                        $search_model->current_or_preferred = 0;
                                          echo $form->field($search_model, 'current_or_preferred')->radioList(array('0'=>'And','1'=>'Or'),['class' => 'current_or_preferred'])->label(''); ?> 
                                         
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Candidate Preferred Location</label>
                                        <div class="col-sm-10">
                                            <?=Html::activeDropDownList($search_model, 'tj_snap_prefered_location', $location_names, ['prompt'=>'- Select Preferred Location -','class' => 'SelectBox form-control','multiple' => 'true'])?>
                                            <?=Html::error($search_model, 'tj_snap_prefered_location',  ['class'=>'help-block help-block-error'])?>
                                        </div>
                                    </div>
                                    <br>
                                    <h4 class="tj-advanced-search-head">Employment Details</h4>
                                    <br>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Functional Area</label>
                                        <div class="col-sm-10">
                                            <?=Html::activeDropDownList($search_model, 'functional_area', $functional_area, ['prompt'=>'- Select Functional Area -','class' => 'SelectBox form-control','multiple' => 'multiple'])?>
                                            <?=Html::error($search_model, 'functional_area',  ['class'=>'help-block help-block-error'])?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Role</label>
                                        <div class="col-sm-10">
                                            <?=Html::activeDropDownList($search_model, 'industry_type', $desination_role, ['prompt'=>'- Select Functional Area -','class' => 'SelectBox form-control','multiple' => 'multiple'])?>
                                            <?=Html::error($search_model, 'industry_type',  ['class'=>'help-block help-block-error'])?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Industry Type</label>
                                        <div class="col-sm-10">
                                            <?=Html::activeDropDownList($search_model, 'role', $industry_type, ['prompt'=>'- Select Functional Area -','class' => 'SelectBox form-control','multiple' => 'multiple'])?>
                                            <?=Html::error($search_model, 'role',  ['class'=>'help-block help-block-error'])?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Employers</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="">
                                        </div>
                                        <div class="col-sm-5">
                                            <?=Html::activeDropDownList($search_model, 'emp_type', array('1'=>'Current Employer','2'=>'Previous Employer','3'=>'Current / Previous'), ['class'=>'form-control'])?>
                                            <?=Html::error($search_model, 'emp_type',  ['class'=>'help-block help-block-error'])?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Designation</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" id="">
                                        </div>
                                        <div class="col-sm-5">
                                            <?=Html::activeDropDownList($search_model, 'designation_emp_type', array('1'=>'Current Employer','2'=>'Previous Employer','3'=>'Current / Previous'), ['class'=>'form-control'])?>
                                            <?=Html::error($search_model, 'designation_emp_type',  ['class'=>'help-block help-block-error'])?>
                                        </div>
                                    </div>
                                    <br>
                                    <h4 class="tj-advanced-search-head">Educational Details</h4>
                                    <br>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">UG Qualification</label>
                                        <div class="col-sm-10">
                                            <?=Html::activeDropDownList($search_model, 'graduation_courses', $graduation_courses, ['prompt'=>'- Select UG Qualification -','class' => 'SelectBox form-control','multiple' => 'multiple'])?>
                                            <?=Html::error($search_model, 'graduation_courses',  ['class'=>'help-block help-block-error'])?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Graduation Specialization</label>
                                        <div class="col-sm-10">
                                            <?=Html::activeDropDownList($search_model, 'graduation_specialization', $specialization, ['class' => 'form-control', 'multiple' => 'multiple'])?>
                                            <?=Html::error($search_model, 'graduation_courses',  ['class'=>'help-block help-block-error'])?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Institute Name</label>
                                        <div class="col-sm-10">
                                            <?=Html::activeDropDownList($search_model, 'ug_institute_name', $university_names, ['prompt'=>'- Select UG Qualification -','class' => 'SelectBox form-control','multiple' => 'multiple'])?>
                                            <?=Html::error($search_model, 'ug_institute_name',  ['class'=>'help-block help-block-error'])?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Year of Passing</label>
                                        <div class="col-sm-1">Between</div>   
                                        <div class="col-sm-4">
                                         <?php                                  
                                                $years = [];
                                                $y = date('Y')+3;
                                                for($i = 0;$i <= 40;$i++){
                                                     $years[$y] = $y;
                                                     $y--;
                                                }?>
                                            <?=Html::activeDropDownList($search_model, 'ug_education_experience_from', $years, ['prompt'=>'- From -','class'=>'form-control'])?>
                                            <?=Html::error($search_model, 'ug_education_experience_from',  ['class'=>'help-block help-block-error'])?>
                                        </div>
                                        <div class="col-sm-1">And</div>   
                                        <div class="col-sm-4">
                                            <?=Html::activeDropDownList($search_model, 'ug_education_experience_to', $years, ['prompt'=>'- To -','class'=>'form-control'])?>
                                            <?=Html::error($search_model, 'ug_education_experience_to',  ['class'=>'help-block help-block-error'])?>
											<div id="ug_error" class="error"></div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <?php
                                        $search_model->ug_education_details = 0;
                                          echo $form->field($search_model, 'ug_education_details')->radioList(array('0'=>'And','1'=>'Or'),['class' => 'ug_education_details'])->label(''); ?> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">PG Qualification</label>
                                        <div class="col-sm-10">
                                             <?=Html::activeDropDownList($search_model, 'post_graduation', $post_graduation_courses, ['prompt'=>'- Select PG Qualification -','class' => 'SelectBox form-control','multiple' => 'multiple'])?>
                                            <?=Html::error($search_model, 'post_graduation',  ['class'=>'help-block help-block-error'])?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Post Graduation Specialization</label>
                                        <div class="col-sm-10">
                                            <?=Html::activeDropDownList($search_model, 'post_graduation_specialization', $specialization, ['class' => 'form-control', 'multiple' => 'multiple'])?>
                                            <?=Html::error($search_model, 'post_graduation_specialization',  ['class'=>'help-block help-block-error'])?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Institute Name</label>
                                        <div class="col-sm-10">
                                            <?=Html::activeDropDownList($search_model, 'pg_institute_name', $university_names, ['prompt'=>'- Select UG Qualification -','class' => 'SelectBox form-control','multiple' => 'multiple'])?>
                                            <?=Html::error($search_model, 'pg_institute_name',  ['class'=>'help-block help-block-error'])?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Year of Passing</label>
                                        <div class="col-sm-1">Between</div>   
                                        <div class="col-sm-4">
                                         <?php                                  
                                                $years = [];
                                                $y = date('Y')+3;
                                                for($i = 0;$i <= 40;$i++){
                                                     $years[$y] = $y;
                                                     $y--;
                                                }?>
                                            <?=Html::activeDropDownList($search_model, 'pg_education_experience_from', $years, ['prompt'=>'- From -','class'=>'form-control'])?>
                                            <?=Html::error($search_model, 'pg_education_experience_from',  ['class'=>'help-block help-block-error'])?>
                                        </div>
                                        <div class="col-sm-1">And</div>   
                                        <div class="col-sm-4">
                                            <?=Html::activeDropDownList($search_model, 'pg_education_experience_to', $years, ['prompt'=>'- To -','class'=>'form-control'])?>
                                            <?=Html::error($search_model, 'pg_education_experience_to',  ['class'=>'help-block help-block-error'])?>
											<div id="pg_error" class="error"></div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapse-01"><i class="fa fa-angle-double-down"></i>
 Show Ph.D / Doctorate Qualification </button>
                                    <div id="collapse-01" class="collapse">
                                        <div class="form-group">
                                            <div class="col-sm-12 col-sm-offset-2">
                                                <?php
                                        $search_model->pg_education_details = 0;
                                          echo $form->field($search_model, 'pg_education_details')->radioList(array('0'=>'And','1'=>'Or'),['class' => 'pg_education_details'])->label(''); ?> 
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="">Doctorate Qualification</label>
                                            <div class="col-sm-10">
                                                <?=Html::activeDropDownList($search_model, 'doctorate', $doctorate_courses, ['prompt'=>'- Select Doctorate Qualification -','class' => 'SelectBox form-control','multiple' => 'multiple'])?>
                                            <?=Html::error($search_model, 'doctorate',  ['class'=>'help-block help-block-error'])?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="">Doctorate Specialization</label>
                                            <div class="col-sm-10">
                                                <?=Html::activeDropDownList($search_model, 'doctorate_specialization', $specialization, ['class' => 'form-control', 'multiple' => 'multiple'])?>
                                            <?=Html::error($search_model, 'doctorate_specialization',  ['class'=>'help-block help-block-error'])?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-sm-2" for="">Institute Name</label>
                                            <div class="col-sm-10">
                                                <?=Html::activeDropDownList($search_model, 'doctorate_institute_name', $university_names, ['prompt'=>'- Select Doctorate Qualification -','class' => 'SelectBox form-control','multiple' => 'multiple'])?>
                                            <?=Html::error($search_model, 'doctorate_institute_name',  ['class'=>'help-block help-block-error'])?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Year of Passing</label>
                                        <div class="col-sm-1">Between</div>   
                                        <div class="col-sm-4">
                                         <?php                                  
                                                $years = [];
                                                $y = date('Y')+3;
                                                for($i = 0;$i <= 40;$i++){
                                                     $years[$y] = $y;
                                                     $y--;
                                                }?>
                                            <?=Html::activeDropDownList($search_model, 'doctorate_education_experience_from', $years, ['prompt'=>'- From -','class'=>'form-control'])?>
                                            <?=Html::error($search_model, 'doctorate_education_experience_from',  ['class'=>'help-block help-block-error'])?>
                                        </div>
                                        <div class="col-sm-1">And</div>   
                                        <div class="col-sm-4">
                                            <?=Html::activeDropDownList($search_model, 'doctorate_education_experience_to', $years, ['prompt'=>'- To -','class'=>'form-control'])?>
                                            <?=Html::error($search_model, 'doctorate_education_experience_to',  ['class'=>'help-block help-block-error'])?>
											<div id="dc_error" class="error"></div>
                                        </div>
                                    </div>
                                    </div>
                                    <br>
                                    <h4 class="tj-advanced-search-head">Affirmative Action</h4>
                                    <br>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Notice Period</label>
                                        <div class="col-sm-10">
                                            <?php
                                              ksort($noticeperiod);
                                            ?>
                                           <?=Html::activeDropDownList($search_model, 'notice_period', $noticeperiod, ['prompt'=>'- Select Notice Period -','class' => 'SelectBox form-control','multiple' => 'multiple'])?>
                                            <?=Html::error($search_model, 'notice_period',  ['class'=>'help-block help-block-error'])?>
                                        
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Category</label>
                                        <div class="col-sm-10">
                                            <?php $category = array('0'=>'General','1' => 'SC','2' => 'ST','3' => 'OBC - Creamy','4' => 'None');?>
                                            <?=Html::activeDropDownList($search_model, 'category', $category, ['prompt'=>'- Select Category -','class' => 'SelectBox form-control','multiple' => 'multiple'])?>
                                            <?=Html::error($search_model, 'category',  ['class'=>'help-block help-block-error'])?>
                                        </div>
                                    </div>
                                    <br>
                                    <h4 class="tj-advanced-search-head">Display Options</h4>
                                    <br>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Candidates Active in Last</label>
                                        <div class="col-sm-10">

                                        <?php $cadidate_active_state = array(
                                    '3' => '3 days','7' => '7 days','15' => '15 days',
                                    '30' => '30 days','60' => '2 months','90' => '3 months',
                                    '120' => '4 months','180' => '6 months','270' => '9 months',
                                    '365' => '1 Year','all' => 'All Resumes','4-7' => '4 days to 7 days',
                                    '8-15' => '8 days to 15 days','16-30' => '16 days to 30 days','30-60' => '30 days to 2 months',
                                    '30-90' => '30 days to 3 months ','60-90' => '2 months to 3 months','60-120' => '2 months to 4 months',
                                    '90-120' => '3 months to 4 months','90-180' => '3 months to 6 months','120-180' => '4 months to 6 months',
                                    '180-270' => '6 months to 9 months','180-365' => '6 months to 1 Year','270-365' => '9 months to 1 Year',
                                    '1 Year+' => '1 Year+');?>
                                        
                                            <?=Html::activeDropDownList($search_model, 'cadidate_active_state', $cadidate_active_state, ['prompt'=>'- Select Category -','class'=>'form-control'])?>
                                            <?=Html::error($search_model, 'annual_salary_lakhs_from',  ['class'=>'help-block help-block-error'])?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Show Candidate Seeking</label>
                                        <div class="col-sm-12">
                                             <?php
                                        $search_model->show_candidate_seeking = '0,1';
                                          echo $form->field($search_model, 'show_candidate_seeking')->radioList(array('0'=>'Permanent Job','1'=>'Temporary/Contract Job','0,1' => 'Any'),['class' => 'pg_education_details'])->label(''); ?> 
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Show Candidate</label>
                                        <div class="col-sm-12">
                                            <?php echo $form->field($search_model, 'show_candidate[]')->checkboxList(['0' => 'Full Time Job', '1' => 'Part Time Job', '2' => 'Freelance', '3' => 'Contract'])->label(''); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-10 col-sm-offset-2">
										  <?= Html::submitButton('Find Resumes',["class"=>"btn btn-default"]) ?>                                            
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