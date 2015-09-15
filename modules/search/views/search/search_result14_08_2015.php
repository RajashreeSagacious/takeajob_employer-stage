<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\UsersOtpModel;
use app\models\TjJobCategory;
use app\models\TjDistrict;
use app\models\Experience;
use app\models\UserSnapshot;
use app\models\UsersProfilePhoto;
use app\assets\AppAsset;
AppAsset::register($this);
$this->title ='Search Results - take.job.com';

$this->registerCssFile(Yii::$app->request->baseUrl.'/css/main02.css',['depends' => [\yii\web\JqueryAsset::className()]]);
$jobseeker_details = UsersOtpModel::find()->asArray()->where(array('in', 'tj_id', Yii::$app->session['jobseeker_ids']))->all();





//echo "<pre>";print_r($jobseeker_details);exit;
 ?>
    

<!--********************************************************************************************** -->
<!--**************************************Main Content Starts Here************************************** -->
<!--********************************************************************************************** -->
    <div class="profile-pages">
        <div class="container resume-fields-bg">
            <div class="col-md-8">
                <div class="p-block">
                    <h1 class="resume-section-title"><i class="fa fa-search"></i>Search for Resume</h1>
                    <h3 class="resume-section-subtitle" style="margin-bottom: 0;">Use our awesome search tool to find the right candidates!</h3>
                    <br>
                    <div id="companies-block">
                        <ul id="recent-listing-ul">
						<?php foreach($jobseeker_details as $candidate_deatils){
							
								$job_category = TjJobCategory::findOne($candidate_deatils['tj_users_job_category']);	
								$job_location = TjDistrict::findOne($candidate_deatils['tj_users_location']);
						
								$image_location = '@web/images/avatar.png';
								$user_profilephoto = UsersProfilePhoto::findOne(['tj_upp_user_id' => $candidate_deatils['tj_id']]); 
								
								if(isset($user_profilephoto)){
									 $image_location = str_replace('@','','http://192.168.1.222/saga/ApplicationTeam/takeajob_jobseeker/'.$user_profilephoto->tj_upp_path_medium);									 
								}
								  $experienceData = Experience::findOne($candidate_deatils['tj_users_experience']);
								  $experience = $experienceData->tj_exp_years;
								  
								  $user_snapshot = UserSnapshot::find()->select('tj_snap_annual_slary_lakhs,tj_snap_annual_salary_thousands')->where(['tj_snap_user_id' => $candidate_deatils['tj_id']])->One();
								 
								  $salary = "NA";
								  if($user_snapshot)
									  $salary = (($user_snapshot -> tj_snap_annual_slary_lakhs != "" || $user_snapshot -> tj_snap_annual_salary_thousands != ""))?'<i class="fa fa-inr"></i>'.$user_snapshot -> tj_snap_annual_slary_lakhs.' - '.($user_snapshot -> tj_snap_annual_slary_lakhs+1).' lakhs/Year':"NA";
								   ?>
							
                            <li id="<?php echo $candidate_deatils['tj_id']; ?>">
                               <a href="<?php echo Yii::$app->homeUrl; ?>search/search/view_candidate_profile?cid=<?php echo $candidate_deatils['tj_id']; ?>" target="_blank">
                                    <div class="recent-listing-holder-block">
                                        <span class="company-list-icon">
											<img class="img-circle" src="<?= $image_location; ?>" alt="Paula Aniston ">                                      
                                        </span>
                                        <span class="recent-list-nameblock">
                                            <span class="recent-list-name"><?php echo $candidate_deatils['tj_users_fname']." ".$candidate_deatils['tj_users_lname'] ?> <span class="resume-prof-title"><?=  $job_category->tj_jc_name; ?></span> </span>
                                            <div class="clear"></div>
                                            <span class="recent-list-location">
                                                <i class="fa fa-map-marker"></i><?= $job_location->tj_dt_districtname; ?>
                                            </span>
                                        </span>
										 <span class="recent-list-view-profile">
                                            <span class="recent-view-profile">
                                                <span class="recent-view-profile-title-holder">
                                                    <span class="recent-view-profile-title">View</span>
                                                    <span class="recent-view-profile-subtitle">Resume</span>
													
                                                </span>
                                                <i class="fa fa-eye"></i>
                                            </span>
                                        </span>										
                                       
                                        <span class="recent-list-badges">
                                            <span class="recent-list-item-post-badge freelance_block">
                                                <span class="recent-list-item-post-badge-job-type" style="width: 110px; line-height: 16px; padding-top: 9px; text-align: right;"><?= $experience; ?> Experience</span>
                                                <span class="recent-list-item-post-badge-amount"> <?= $salary; ?></span>
                                                <span class="recent-list-item-post-badge-amount-per"></span>
                                            </span>
                                        </span>
                                    </div>
                                </a>
                            </li>
						<?php  } ?>
                          
                        </ul>
                    </div>   
                </div>
            </div>
            <div class="col-md-4">
                <div class="carousel-inner" role="listbox">
                    <div class="item active">
                        <span class="filters-title"><i class="fa fa-star"></i>FEATURED RESUMES!</span>
                        <div class="sidebar-featured-item">
                            <div class="featured-item">
                                <a href="#">
                                    <span class="featured-item-image"><img class="big-img" src="http://alexgurghis.com/themes/wpjobus/wp-content/uploads/bfi_thumb/cover-163-2x0cqebr93rcmoh7b7n5sa.jpg" alt="Online PR manager">
                                        <span class="featured-item-content-title-logo">
                                            <span class="featured-item-content-title-logo-img">
                                                <span class="helper"></span>
                                                <!--img src="http://alexgurghis.com/themes/wpjobus/wp-content/uploads/bfi_thumb/tumblr_mw2b8qu8IW1r46py4o1_1280-2ww3jxmfh88cy98kdhp2x6.jpg" alt=""-->
                                            </span>
                                        </span>
                                    </span>
                                    <span class="featured-item-badge">
                                        <span class="featured-item-job-badge">
                                            <span class="featured-item-job-badge-title">8 Years</span>
                                            <span class="featured-item-job-badge-info">
                                                <span class="featured-item-job-badge-info-sum"><i class="fa fa-inr"></i> 900000 / </span>
                                                <span class="featured-item-job-badge-info-per"> Yearly</span>
                                            </span>
                                        </span>
                                    </span>
                                    <span class="featured-item-content">
                                        <span class="featured-item-content-title">Alex Gurghis</span>
                                        <span class="featured-item-content-tagline" style="color: #999999;
  font-size: 18px;,">Senior Branding &amp; UI/UX Expert</span>
                                        <span class="featured-item-content-subtitle">
                                            <span><i class="fa fa-map-marker"></i>New York</span>
                                        </span>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="filters">
                    <span class="filters-title">Search &amp; Refinements</span>
                    <div class="full sidebar-widget-bottom-line">
                        <div class="full" style="margin-bottom: 0;">
                            <input type="text" class="form-control" name="comp_keyword" id="comp_keyword" value="" placeholder="Type and press enter..." style="margin-bottom: 15px;">
                            <div id="search-results"></div>
                        </div>
                        <div class="full">
                            <div class="one_half first" style="margin-bottom: 0;">
                                <span class="filters-subtitle">Career Level</span>
                                <div class="filters-lists">
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">All Types</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Senior</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Junior</label>
                                    </div>
                                    <div class="checkbox ">
                                        <label><input type="checkbox" value="">Middle</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Expert</label>
                                    </div>
                                </div>
                            </div>
                            <div class="one_half" style="margin-bottom: 0;">
                                <span class="filters-subtitle">Presence</span>
                                <div class="filters-lists-main">
                                    <div class="filters-lists">
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="">All Types</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="">Senior</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="">Junior</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="">Middle</label>
                                        </div>
                                        <div class="checkbox">
                                            <label><input type="checkbox" value="">Expert</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="full sidebar-widget-bottom-line">
                        <span class="filters-subtitle">Experience</span>
                        <div class="one_half first">
                            <p>More than <span class="comp_est_year_num">11</span> years</p>
                        </div>
                        <div class="one_half">
                            <div id="advance-search-slider">
                                <form>
                                    <input type="range" name="points" min="0" max="10">
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="full sidebar-widget-bottom-line">
                        <span class="filters-subtitle">Salary</span>
                        <div class="full form-inline">
                            <p class="comp_team_holder" style="margin-bottom: 0;">From
                                <input class="form-control" type="text" name="comp_min_team" id="comp_min_team" value=""> to
                                <input class="form-control" type="text" name="comp_max_team" id="comp_max_team" value="">
                            </p>
                        </div>
                    </div>
                    <div class="full sidebar-widget-bottom-line">
                        <div class="one_half first" style="margin-bottom: 0;">
                            <span class="filters-subtitle">JOB TYPES</span>
                            <div class="filters-lists">
                                <div class="checkbox">
                                    <label><input type="checkbox" value="">All Types</label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" value="">Freelance</label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" value="">Part Time</label>
                                </div>
                                <div class="checkbox ">
                                    <label><input type="checkbox" value="">Full Time</label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" value="">Internship</label>
                                </div>
                                <div class="checkbox">
                                    <label><input type="checkbox" value="">Volunteer</label>
                                </div>
                            </div>
                        </div>
                        <div class="one_half" style="margin-bottom:30px;">
                            <span class="filters-subtitle">LOCATIONS</span>
                            <div class="filters-lists-main">
                                <div class="filters-lists">
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">All Locations</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Bangalore</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Chennai</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Kolkata</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Delhi</label>
                                    </div>
                                    <div class="checkbox">
                                        <label><input type="checkbox" value="">Mumbai</label>
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

    <script>
        $(document).ready(function() {
            $('input').iCheck({
                checkboxClass: 'icheckbox_square-green',
                radioClass: 'iradio_square-green',
                increaseArea: '20%' // optional
            });
        });
    </script>

