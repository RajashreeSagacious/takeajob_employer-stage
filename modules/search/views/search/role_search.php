<?php 
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\ItSkills;
use app\models\JobSubCategory;
use app\models\TjJobCategory;
use app\models\TjDistrict;
use app\assets\AppAsset;

$this->title ='Role Search - employer.takeajob.com';

$JobCategoryData = TjJobCategory::find()->orderBy('tj_jc_name')->all();
$jobCategory = ArrayHelper::map($JobCategoryData,'tj_jc_id','tj_jc_name');



?>
<style>
.error{
	color:#a94442;
}
</style>

<section class="tj-role-search">
	<div class="container">
		<div class="row">
			<div class="col-md-9 tj-role-search-design">
                <div class="search-resume-tab-holder">
                    <ul class="nav nav-tabs">
                       <li><a href="<?php echo Url::to(['search/advanced_search']); ?>" >Advanced Search</a></li>                  
					   <li><a href="<?php echo Url::to(['search/easy_search']); ?>" >Easy Search</a></li>
						<li><a href="<?php echo Url::to(['search/special_search']); ?>" >Special Search</a></li>                   
						<li class="active"><a href="<?php echo Url::to(['search/role_search']); ?>" >Role Search</a></li> 
                    </ul>
                    <div class="tab-content">
                        <div id="role-search" class="tab-pane fade in active">
                            <h4 class="role-search-text"><i class="fa fa-filter padding-right"></i>Role Search</h4>							
                            <!-- <p class="text-caps">Create your Account Details</p> -->
                            <div class="tj-role-search-input">
                                <!-- <h4 class="tj-role-search-head">Basic Details</h4> -->
                                <?php $form = ActiveForm::begin(['id'=>'role_search']);  ?>	
                                    <div class="form-group">
                                        <div class="col-sm-10 col-sm-offset-2">
                                            <div class="auto-fill-block">
											 <?php
											  foreach($jobCategory as $key=>$val){
												echo '<div class="checkbox"><label>';
												echo '<input type="checkbox" class="roles" name="selected_roles[]" value="'.$key.'">';
												echo $val;
												echo '</label></div>';
												}
												echo '<div id="search_error" class="error"></div>';
											 ?>											 
											 
                                               
                                            </div>
                                            <div class="subcategory">
                                                <div class="panel panel-default" style="display:none">
                                                    <div class="panel-body" >
                                                        <div class="auto-fill-block" id="more_roles" >
                                                            
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label col-sm-2" for="">Candidates Active in Last</label>
                                        <div class="col-sm-10">
										<?php
										$role_search_model->cadidate_active_state = 180;
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
                                        
                                       echo $form->field($role_search_model,'cadidate_active_state')->dropDownList($cadidate_active_state,['class' => 'form-control'])->label(false); ?>										
										
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="col-sm-10 col-sm-offset-2">
										<?= Html::submitButton('Find Resumes',["class"=>"btn btn-primary"]) ?>
                                           
                                        </div>
                                    </div>
                                <?php  ActiveForm::end(); ?>
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

<script>
function showValues() {	
    var str = $( "#role_search" ).serialize();
    $( "#results" ).text( str );
		$.ajax({
			  type: "POST",
			  url: "<?php echo \yii\helpers\Url::toRoute(['/search/search/role_more'])?>",
			  data: str,			 
			  success: function( response ) {
						 $('.panel-default').show('');
						 $('#more_roles').html(response);				
			  }
		});	
     }			
  $( document ).on( "change", "input.roles", showValues ); 
</script>



