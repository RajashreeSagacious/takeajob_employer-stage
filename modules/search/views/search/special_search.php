<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\ItSkills;
use app\models\JobSubCategory;
use app\models\TjDistrict;
use app\assets\AppAsset;

$this->title ='Special Search - employer.takeajob.com';


?>
  <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <section class="tj-special-search">
	<div class="container">
		<div class="row">
			<div class="col-md-9 tj-special-search-design">
                <div class="search-resume-tab-holder">
                    <ul class="nav nav-tabs">
                        <li><a href="<?php echo Url::to(['search/advanced_search']); ?>" >Advanced Search</a></li>                  
	                    <li><a href="<?php echo Url::to(['search/easy_search']); ?>" >Easy Search</a></li>
	                    <li  class="active"><a href="<?php echo Url::to(['search/special_search']); ?>" >Special Search</a></li> 
						<li><a href="<?php echo Url::to(['search/role_search']); ?>" >Role Search</a></li>	
                    </ul>
                    <div class="tab-content">
                        <div id="special-search" class="tab-pane fade in active">
                            <h4 class="special-search-text"><i class="fa fa-sliders padding-right"></i>Special Search</h4>
                            <!-- <p class="text-caps">Create your Account Details</p> -->
                            <div class="tj-special-search-input">
                                <!-- <h4 class="tj-special-search-head">Basic Details</h4> -->
                                <?php $form = ActiveForm::begin(['id'=>'search_form']);  ?>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Candiadte Name</label>
                                        <div class="col-sm-10">
                                            <?php echo $form->field($special_search_model,'search_by_name')->textInput(['class' => 'form-control'])->label(false); 
										 echo '<div id="name_error" class="error"></div>'; ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Candiadte Contact No</label>
                                        <div class="col-sm-10">
                                          <?= $form->field($special_search_model,'search_by_phone_no')->textInput(['class' => 'form-control'])->label(false); 
										 echo '<div id="phoneno_error" class="error"></div>';?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Active in Last</label>
                                        <div class="col-sm-10">
                                            <?php
									$special_search_model->cadidate_active_state = 180;
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
									
									echo $form->field($special_search_model,'cadidate_active_state')->dropDownList($cadidate_active_state,['class' => 'form-control'])->label(false); 
											?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-10 col-sm-offset-2">
										<?= Html::submitButton('Find Resumes',["class"=>"btn btn-primary"]) ?>                                           
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
	<script>
		$("#search_form").submit(function(){
		if(($('#advancesearch-search_by_name').val() == '') && ($('#advancesearch-search_by_phone_no').val() == '')){
			$('#name_error').html('Please enter candidate Name / Phone No to fine resumes !');
			return false;
		}
		/* if($('#advancesearch-search_by_name').val() == ''){
			$('#name_error').html('Candidate Name should not be blank !');
			return false;
		}
		if($('#advancesearch-search_by_phone_no').val() == ''){
			$('#phoneno_error').html('Candiadte Contact No should not be blank !');
			return false;
		} */
	  });
	</script>
<style>
.error{
	color:#a94442;
}
</style>
<!--********************************************************************************************** -->
<!--**************************************Main Content Ends Here************************************** -->
<!--********************************************************************************************** -->


 
