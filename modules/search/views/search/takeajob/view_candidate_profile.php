<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\TjLanguages;
use app\models\TjCountries;
use app\models\JobSubCategory;
use app\models\Skills;
use app\models\Graduation_Specialization;
use app\models\Education;
use app\models\UsersMoreDetails;
use app\models\University;
use app\assets\UserAppAsset;
use app\models\UsersProfilePhoto;

use yii\bootstrap\Modal;

$this->title ='Candidate Profile - employeer.takeajob.com';

UserAppAsset::register($this);
$user_id = $_GET['cid'];
$more_details = UsersMoreDetails::findOne(['tj_users_mdetails_user_id' => $user_id]);
		
if($more_details){
		$emp_type = explode(',',$more_details->tj_users_mdetails_employment_type);		
		 foreach($emp_type as $k=>$j){
			$emp_types[] = ($j == 0)?"Full Time":(($j == 1)?"Part Time":(($j == 2 )?"Freelance":(($j == 3)?"Contract":"")));
												
		  } 
		}
        $employment_type = implode(', ',$emp_types);		
		
?>
<link href='http://fonts.googleapis.com/css?family=Roboto:400,500,700' rel='stylesheet' type='text/css'>

<section class="tj-profile-view">
	<div class="container">
		<div class="row">
			<div class="col-md-12 tj-profile-view-design">
				<h4 class="profile-view-text"><i class="fa fa-user padding-right"></i>View Profile</h4>
				<br>
				<div class="col-md-2">
					<div class="profile-pic">
					<?php 
					    $image_location = Yii::$app->getUrlManager()->getBaseUrl().'/images/avatar.png';
						$user_profilephoto = UsersProfilePhoto::findOne(['tj_upp_user_id' => $user_id]); 
						
						if(isset($user_profilephoto))                                                                              
						$image_location = yii::$app->params['jobseeker_url'].''.str_replace('@web','',$user_profilephoto->tj_upp_path_medium);
					?>
						<img src="<?= $image_location?>" class="img-responsive">
					</div>
				</div>
				<div class="col-md-6">
					<div class="profile-name"><h3><?php echo ucwords($user_model -> tj_users_fname).' '. $user_model -> tj_users_lname ; ?></h3></div>
					<div class="profile-desi"  style="font-size: 15px;"><?php echo isset($designation)?$designation:$job_role;?></div>
					<div class="profile-Exp"><i class="fa fa-suitcase padding-right"></i><?php echo $experience." Exp"; ?></div>
					<?php $sal = $user_snapshot -> tj_snap_annual_slary_lakhs.' - '.($user_snapshot -> tj_snap_annual_slary_lakhs+1).' lakhs Per Annum';?>
					<div class="profile-salary"><i class="fa fa-money padding-right"></i><?= $sal ?></div>
					<div class="profile-time"><i class="fa fa-clock-o padding-right"></i><?= $employment_type?></div>
					<div class="profile-Skills"><i class="fa fa-key padding-right"></i><?php echo strtoupper($user_skill_all);?></div>
				</div>
				<div class="col-md-4">
					<div class="profile-about-job">
						<p>This space explains the recruiter about your experience, annual income and the type of job you are looking for. Here, you can download the resume, which you have uploaded.</p>
					</div>
					<div class="profile-resume-download">
						<button type="button" class="btn btn-primary">
						<?php echo Html::a('<i class="fa fa-download padding-right"></i>&nbsp;&nbsp;Download Resume', Url::to(['/search/search/download','id'=>$user_id]),['style'=>'text-decoration: none; color: #fff; font-weight: bold;float:right; font-size:14px;']); ?>
						</button>
					</div>					
				</div>
  			</div>
		</div>
	</div>
</section>

<section class="tj-profile-view">
	<div class="container">
		<div class="row">
			<div class="col-md-9 tj-profile-view-design">
		   <?php 
		 		   
		   if($summary_content != ""){					
					echo '<h4 class="profile-view-text"><i class="fa fa-info-circle padding-right"></i> About me in brief</h4>
						<p class="about-text">'.nl2br($summary_content).'</p>';
					
				 }?>
				<h4 class="profile-view-text"><i class="fa fa-newspaper-o padding-right"></i> Profile Synopsis</h4>
				<br>
				<div class="profile-synopsis">
					<table class="table table-hover">
					  <tbody>
					  	<tr class="pro-bg-g"><td><span class="pro-head">Resume Headline:</span></td><td><?php echo $user_snapshot -> tj_snap_resume_headline; ?></td></tr>
					  	<tr class="pro-bg-w"><td><span class="pro-head">Current Designation:</span></td><td><?php echo isset($designation)?$designation:"NA"; ?></td></tr>
					  	<tr class="pro-bg-g"><td><span class="pro-head">Current Company:</span></td><td><?php echo isset($current_employer_data->tj_employer_name)?$current_employer_data->tj_employer_name:"NA"; ?></td></tr>
					  	<tr class="pro-bg-w"><td><span class="pro-head">Current Location:</span></td><td><?php echo $user_location;?></td></tr>
					  	<tr class="pro-bg-g"><td><span class="pro-head">Preferred Location:</span></td><td><?php echo $user_prefered_location;?></td></tr>
					  	<tr class="pro-bg-w"><td><span class="pro-head">Functional Area:</span></td><td><?php echo $job_category; ?></td></tr>
					  	<tr class="pro-bg-w"><td><span class="pro-head">Role:</span></td><td><?php echo $job_role;?></td></tr>
					  	<tr class="pro-bg-g"><td><span class="pro-head">Industry:</span></td><td><?php echo $user_industry; ?></td></tr>
					  	<tr class="pro-bg-w"><td><span class="pro-head">Date of Birth:</span></td><td><?php echo ($user_snapshot -> tj_snap_dob == "0000-00-00")?"Not Mentioned":date("F jS, Y",strtotime($user_snapshot -> tj_snap_dob));?></td></tr>
					  	<tr class="pro-bg-g"><td><span class="pro-head">Gender:</span></td><td><?php echo ($user_snapshot -> tj_snap_gender)?(($user_snapshot -> tj_snap_gender == 1)?  'Male' : 'Female'):"Not Mentioned"; ?></td></tr>
					  	<tr class="pro-bg-w"><td><span class="pro-head">Key Skills:</span></td><td><?php echo strtoupper($user_skill_all);?></td></tr>
					  	<tr class="pro-bg-g"><td><span class="pro-head">Total Experience:</span></td><td><?php echo $experience;?></td></tr>
					  	<tr class="pro-bg-w"><td><span class="pro-head">Annual Salary:</span></td><td>  Rs. <?php echo $user_snapshot -> tj_snap_annual_slary_lakhs;?> lakh(s) <?php echo $user_snapshot -> tj_snap_annual_salary_thousands;?> thousand(s)</td></tr>
					  	<tr class="pro-bg-g"><td><span class="pro-head">Highest Degree:</span></td><td><?php echo $user_education;?></td></tr>
					  	<tr class="pro-bg-w"><td><span class="pro-head">Phone:</span></td><td><?php echo $user_model -> tj_users_phone;?></td></tr>
					  	<tr class="pro-bg-g"><td><span class="pro-head">Email:</span></td><td><?php echo $user_model -> tj_users_email; ?></td></tr>
					  	<tr class="pro-bg-w"><td><span class="pro-head">Permanent Address:</span></td><td> <?php echo $user_model -> tj_users_map_address;?></td></tr>
					  	<tr class="pro-bg-g"><td><span class="pro-head">Hometown/City:</span></td><td><?php echo $users_city;?></td></tr>
					  	<tr class="pro-bg-w"><td><span class="pro-head">Pin Code:</span></td><td><?php echo $user_snapshot -> tj_snap_mailing_pin;?></td></tr>
					  	<tr class="pro-bg-g"><td><span class="pro-head">Marital Status:</span></td><td><?php
						            	
						            	if($user_snapshot -> tj_snap_marital_status){ 
					            			if($user_snapshot -> tj_snap_marital_status == 'N')
					            				echo "Single/Unmarried";
					            			else if($user_snapshot -> tj_snap_marital_status == 'M')	
					            				echo "Married";
					            			else if($user_snapshot -> tj_snap_marital_status == 'W')	
					            				echo "Widowed";
					            			else if($user_snapshot -> tj_snap_marital_status == 'D')	
					            				echo "Divorced";
					            			else if($user_snapshot -> tj_snap_marital_status == 'S')	
					            				echo "Separated";
					            			else if($user_snapshot -> tj_snap_marital_status == 'O')	
					            				echo "Other";
						            	  }else{
						            	  	echo "Not Mentioned";
						            	  }
						            	?></td></tr>
					  </tbody>
					</table>
					<br>
				</div>

				<h4 class="profile-view-text"><i class="fa fa-graduation-cap padding-right"></i>Academic Credentials</h4><br>
			<?php 
			 if($user_qualification){			 	
				foreach ($user_qualification as $key => $qualification_details){
					$university_name = University::findOne($qualification_details['tj_uq_institute']);
					$education_type = Education::findOne($qualification_details['tj_uq_qualification']);
					$specialised_in = Graduation_Specialization::findOne($qualification_details['tj_uq_specialization']);
					
					$specialisation =  ($qualification_details['tj_uq_status'] == 1)?" Bachelors Education ":(($qualification_details['tj_uq_status'] == 2)?" Masters Education":(($qualification_details['tj_uq_status'] == 3)?"Doctorate/Phd":(($qualification_details['tj_uq_status'] == 4)?"Class 10th":"Class 12th")));
					
					$type =  ($qualification_details['tj_uq_status'] == 1)?"Graduation":(($qualification_details['tj_uq_status'] == 2)?"Post Graduation":(($qualification_details['tj_uq_status'] == 3)?"Doctorate":(($qualification_details['tj_uq_status'] == 4)?"Class 10th":"Class 12th")));
					
					$course_type = ($qualification_details['tj_uq_education_type'] == 1)?" Full Time ":(($qualification_details['tj_uq_status'] == 2)?" Part Time ":" Correspondence/Distance learning ");
					echo '<h4 class="tj-register-head">'.$specialisation.'</h4>
							<div class="profile-synopsis">
								<table class="table table-hover">
								  <tbody>
									<tr class="pro-bg-g"><td><span class="pro-head">'.$type.':</span></td><td>'.$education_type->tj_edu_title.'</td></tr>
									<tr class="pro-bg-w"><td><span class="pro-head">'.$type.' Type:</span></td><td>'.$course_type.'</td></tr>
									<tr class="pro-bg-g"><td><span class="pro-head">'.$type.' Specialization:</span></td><td>'.$specialised_in->tj_es_name.'</td></tr>
									<tr class="pro-bg-w"><td><span class="pro-head">'.$type.' University Institute:</span></td><td>'.$university_name->tj_uni_name.'</td></tr>
									<tr class="pro-bg-g"><td><span class="pro-head">'.$type.' Year:</span></td><td>'.date('F jS, Y',strtotime($qualification_details['tj_uq_duration_start'])).'  To  '.date('F jS, Y',strtotime($qualification_details['tj_uq_duration_end'])).'</td></tr>
									<tr class="pro-bg-w"><td><span class="pro-head">'.$type.' CGPA / %:</span></td><td>'.$qualification_details['tj_uq_aggregate'].' </td></tr>
								  </tbody>
								</table>
							</div>';
				 
				}
			}
			?>
				

				
				 <?php  if($user_projects){
							echo '<h4 class="profile-view-text"><i class="fa fa-cogs padding-right"></i>Professional Assignments</h4><br>';
					 
							  foreach($user_projects as $key => $project_details){						     	      	
								if($project_details['tj_uprj_skills']){
									$skillArray = explode(',',$project_details['tj_uprj_skills']);										
									foreach($skillArray as $key => $val){												
										$skillData = Skills::findOne([$val]);												
										$project_all_skills[] = $skillData -> tj_sk_name;
									}											
								}  
								$project_skills = implode(', ',$project_all_skills);
							
								$employement_type =($project_details['tj_uprj_employment_type'] == 1)?"Full Time":(($project_details['tj_uprj_employment_type'] == 2)?"Part Time":(($project_details['tj_uprj_employment_type'] == 3)?"Contract":" "));
								$site = ($project_details['tj_uprj_location'] == 1)?"Onsite":(($project_details['tj_uprj_location'] == 2)?"Offsite":" ");
								
								
								$roleData = JobSubCategory::findOne($project_details['tj_uprj_role']);
								$project_role = $roleData -> tj_jsc_name;
									
								
								echo '<h4 class="tj-register-head">'.$project_details['tj_uprj_title'].'</h4>
									<div class="profile-synopsis">
										<table class="table table-hover">
										  <tbody>';
										  echo ($project_details['tj_uprj_client'])?'<tr class="pro-bg-g"><td><span class="pro-head">Client:</span></td><td>'.$project_details['tj_uprj_client'].'</td></tr>':'';
										  echo ($project_role)?'<tr class="pro-bg-w"><td><span class="pro-head">Role</span></td><td>'.$project_role.'</td></tr>':"";
										  echo ($employement_type)?'<tr class="pro-bg-g"><td><span class="pro-head">Employement Type:</span></td><td>'.$employement_type.'</td></tr>':"";
											
										  echo ($site)?'<tr class="pro-bg-w"><td><span class="pro-head">Project Location:</span></td><td>'.$site.'</td></tr>':'';	
										  echo ($project_details['tj_uprj_team_size'])?'<tr class="pro-bg-g"><td><span class="pro-head">Team Size:</span></td><td>'.$project_details['tj_uprj_team_size'].'</td></tr>':'';	
											
										  echo ($project_skills)?'<tr class="pro-bg-w"><td><span class="pro-head">Skills:</span></td><td>'.$project_skills.'</td></tr>':''; 
											
										  echo '</tbody>
										</table>
									</div>';
							  }
				      }?>				  
				
				
				
						<?php
						//echo "<pre>";print_r($user_snapshot);exit;
							if(!empty($employer_data)){
								echo '<h4 class="profile-view-text"><i class="fa fa-history padding-right"></i>Work History</h4><br>';
								 foreach($employer_data as $key => $employerDetails){
									//echo date("Y-m-d H:i:s");exit;
									 $toDate = ($employerDetails['tj_employer_status_of_employer'] == 1)?" Till Date ":date("F jS, Y",strtotime($employerDetails['tj_employer_duration_to']));
									 $employerDetails['tj_employer_duration_to'] = ($employerDetails['tj_employer_status_of_employer'] == 1)?date("Y-m-d H:i:s"):$employerDetails['tj_employer_duration_to'];
								     $interval = ($employerDetails['tj_employer_status_of_employer'] != 1)?(date_diff(date_create($employerDetails['tj_employer_duration_from']), date_create($employerDetails['tj_employer_duration_to']))):(date_diff(date_create($employerDetails['tj_employer_duration_from']), date_create(date('Y-m-d H:i:s'))));
									 $format_year = ($interval->format('%y') == 0)?"":$interval->format('%y').' Years ';
									 $format_month = ($interval->format('%m') == 0)?"":$interval->format('%m').' Months ';
									 $format_day = ($interval->format('%d') == 0)?"":$interval->format('%d').' Days ';
									 $duration =  $format_year.''.$format_month.''.$format_day;
									 $employerStatus = ($employerDetails['tj_employer_status_of_employer'] == 1)?"Currently Employer ":"Previously Employer";
									 
									 $notice = NoticePeriod::findOne($employerDetails['tj_employer_notice']);									 
									 $notice_period = $notice['tj_notice'];								
									
									$desig_data = JobSubCategory::findOne($employerDetails['tj_employer_designation']);
									$designation = $desig_data -> tj_jsc_name;

									echo '<h4 class="tj-register-head">'.$employerStatus.'</h4>
									<div class="profile-synopsis">
										<table class="table table-hover">
										  <tbody>
											<tr class="pro-bg-g"><td><span class="pro-head">Employer Name:</span></td><td>'.$employerDetails['tj_employer_name'].'</td></tr>
											<tr class="pro-bg-w"><td><span class="pro-head">Employer Designation:</span></td><td>'.$designation.'</td></tr><tr class="pro-bg-g"><td><span class="pro-head">Annual Salary:</span></td><td>'.$user_snapshot ->tj_snap_annual_slary_lakhs.' Lakhs and '.$user_snapshot -> tj_snap_annual_salary_thousands.' Thousands</td></tr>';
											echo ($notice_period)?'<tr class="pro-bg-w"><td><span class="pro-head">Employer Notice:</span></td><td>'.$notice_period.'</td></tr>':'';
											echo '<tr class="pro-bg-g"><td><span class="pro-head">Duration:</span></td><td>'.date("F jS, Y",strtotime($employerDetails['tj_employer_duration_from'])).'  TO  '.$toDate.'</td></tr>
											<tr class="pro-bg-w"><td><span class="pro-head">Employer Job Profile:</span></td><td>'.$employerDetails['tj_employer_job_profile'].'</td></tr>
										  </tbody>
										</table>
									</div>';
									
							   } 
						  	} 
					  	?>			
				<?php if(!empty($it_skills_data)){
		              echo '<br/>
							<h4 class="profile-view-text"><i class="fa fa-info-circle padding-right"></i>Technical Skill Set</h4>
							<br/>
							<div class="profile-synopsis">
								<table class="table table-hover">
									<thead>
									<tr>
									  <th>Skill Name</th>
									  <th>Version</th>
									  <th>Years</th>
									  <th>Months</th>
									</tr>
								  </thead>';
		                	foreach($it_skills_data as $key => $Skill){
		                		$exp_in_years = ($Skill['tj_skill_year'])?$Skill['tj_skill_year']:" ";
		                		$exp_in_months = ($Skill['tj_skill_month'])?$Skill['tj_skill_month']:"";
		                		$skill_version = ($Skill['tj_skill_version'])?$Skill['tj_skill_version']:" NE ";
								echo '<tbody>
										<tr class="pro-bg-g"><td><span class="pro-head">'.$Skill['tj_skill_names'].'</span></td><td>'.$skill_version.'</td> <td>'.$exp_in_years.'</td><td>'.$exp_in_months.'</td></tr>											
									</tbody>';			                	
		                	}
		                  echo '</table></div>';	
		         }				
				
			
			     if($more_details){ 
		            	$countries = TjCountries::findOne($more_details->tj_users_mdetails_other_countries);
		            	$job_type = ($more_details->tj_users_mdetails_job_type == '0')?"Permanent":(($more_details->tj_users_mdetails_job_type == '1')?"Temporary/Contractual":(($more_details->tj_users_mdetails_job_type == '0,1')?"Permanent, Temporary/Contractual":""));	  
		            	$emp_type = explode(',',$more_details->tj_users_mdetails_employment_type);
		            	foreach($emp_type as $k=>$j){
		            		$emp_type[$k] = ($j == 0)?"Full Time":(($j == 1)?"Part Time":(($j == 2 )?"Freelance":(($j == 3)?"Contract":"")));
		            		
		            	}
		                $user_employement_type = implode(',',$emp_type); 
		                $Category = ($more_details->tj_users_mdetails_categories == 0)?"General":(($more_details->tj_users_mdetails_categories == 1)?"SC":(($more_details->tj_users_mdetails_categories == 2 )?"ST":(($more_details->tj_users_mdetails_categories == 3)?"OBC- Creamy":(($more_details->tj_users_mdetails_categories == 4)?"None":""))));
		                $work_categories = ($more_details->tj_users_mdetails_work_categories == 0)?"Have H1 Visa":(($more_details->tj_users_mdetails_work_categories == 1)?"Need H1 Visa":(($more_details->tj_users_mdetails_work_categories == 2 )?"TN Permit Holder":(($more_details->tj_users_mdetails_work_categories == 3)?"Green Card Holder":(($more_details->tj_users_mdetails_work_categories == 4)?"US Citizen":(($more_details->tj_users_mdetails_work_categories == 5)?"Authorized to work in US":(($more_details->tj_users_mdetails_work_categories == 6)?"None":""))))));		                
		                $physically_challenged = ($more_details->tj_users_mdetails_physicallyChallenged == 0)?"Yes":"No";		                
		                
						
						
				echo '<h4 class="profile-view-text"><i class="fa fa-info-circle padding-right"></i>Additional information</h4><br>
					<h4 class="tj-register-head">Desired Job Information</h4>
					<div class="col-md-12 profile-synopsis">
						<div class="col-md-4">
						<h4 class="tj-register-head">Desired Job Information</h4>
						<table class="table table-hover">
						  <tbody>
							<tr class="pro-bg-g"><td><span class="pro-head">Job Type:</span></td><td>'.$job_type.'</td></tr>
							<tr class="pro-bg-w"><td><span class="pro-head">Employment Type:</span></td><td>'.$user_employement_type.'</td></tr>
						  </tbody>
						</table>
						</div>
						<div class="col-md-4">
						<h4 class="tj-register-head">Affirmative Action</h4>
						<table class="table table-hover">
						  <tbody>
							<tr class="pro-bg-g"><td><span class="pro-head">Categories:</span></td><td>'.$Category.'</td></tr>
							<tr class="pro-bg-w"><td><span class="pro-head">Physically Challenged:</span></td><td>'.$physically_challenged.'</td></tr>
						  </tbody>
						</table>
						</div>
						<div class="col-md-4">
						<h4 class="tj-register-head">Work Authorization</h4>
						<table class="table table-hover">
						  <tbody>
							<tr class="pro-bg-g"><td><span class="pro-head">Work Categories:</span></td><td>'.$work_categories.'</td></tr>
							<tr class="pro-bg-w"><td><span class="pro-head">Other Countries:</span></td><td>'.$countries->tj_countryname.'</td></tr>
						  </tbody>
						</table>
						</div>
					</div>';

				 }
		      ?>
			<?php if($Known_languages){
				echo '<div class="col-md-12 profile-synopsis">
						<br/><h4 class="profile-view-text"><i class="fa fa-info-circle padding-right"></i>Known Languages</h4><br/>
						<div class="profile-synopsis">
							<table class="table table-hover">
								<thead>
								<tr>
								  <th>Language</th>
								  <th>Read</th>
								  <th>Write</th>
								  <th>Speek</th>
								  <th>Proficiency</th>
								</tr>
					</thead>
					<tbody>';
				 foreach($Known_languages as $key => $languages){
							$language_name = TjLanguages::findOne($languages['tj_users_klang_name']);
							$read = ($languages->tj_users_klang_read == 1)?"check":"close";
							$write = ($languages->tj_users_klang_write == 1)?"check":"close";
							$speak = ($languages->tj_users_klang_speak == 1)?"check":"close";
							$proficiency = ($languages->tj_users_klang_proficiency == 0)?"Beginner":(($languages->tj_users_klang_proficiency == 1)?"Proficient":"Expert");
					echo '<tr class="pro-bg-g"><td><span class="pro-head">'.$language_name->tj_lang_name.'</span></td><td><i class="fa fa-'.$read.'"></i></td> <td><i class="fa fa-'.$write.'"></i></td> <td><i class="fa fa-'.$speak.'"></i></td><td>'.$proficiency.'</td></tr>';
			            
				 }
			  echo ' </tbody>
						</table>
					</div>
				</div>';
			}?>
			

				<div class="col-md-12 profile-synopsis">
					<br/>
				<h4 class="profile-view-text"><i class="fa fa-info-circle padding-right"></i>Contact Details</h4>
				<br/>
					<div class="profile-synopsis">
						<table class="table table-hover">
						  <tbody>
						  	<tr class="pro-bg-g"><td><span class="pro-head">Name:</span></td><td><?php echo ucwords($user_model -> tj_users_fname).' '. $user_model -> tj_users_lname ; ?></td></tr>
						  	<tr class="pro-bg-w"><td><span class="pro-head">Address:</span></td><td> <?= $user_model -> tj_users_map_address?> </td></tr>
						  	<tr class="pro-bg-g"><td><span class="pro-head">Email:</span></td><td> <?= $user_model -> tj_users_email;?> </td></tr>
						  	<tr class="pro-bg-w"><td><span class="pro-head">Current Location:</span></td><td><?php echo $user_location;?></td></tr>
						  	<tr class="pro-bg-g"><td><span class="pro-head">Mobile:</span></td><td><?php echo $user_model -> tj_users_phone;?></td></tr>
						  	<tr class="pro-bg-w"><td><span class="pro-head">Twitter:</span></td><td><?= ($user_model['tj_users_twitter_url'])?$user_model['tj_users_twitter_url']:"Not Mentioned"; ?></td></tr>
						  	<tr class="pro-bg-g"><td><span class="pro-head">Facebook:</span></td><td><?= ($user_model['tj_users_facebook_url'])?$user_model['tj_users_facebook_url']:"Not Mentioned"; ?></td></tr>
						  	<tr class="pro-bg-w"><td><span class="pro-head">Google Plus:</span></td><td><?= ($user_model['tj_users_googleplus_url'])?$user_model['tj_users_googleplus_url']:"Not Mentioned"; ?></td></tr>
						  	<tr class="pro-bg-g"><td><span class="pro-head">LinkedIn:</span></td><td><?= ($user_model['tj_users_linkedin_url'])?$user_model['tj_users_linkedin_url']:"Not Mentioned"; ?> </td></tr>
						  	<tr class="pro-bg-w"><td><span class="pro-head">Skype:</span></td><td><?= ($user_model['tj_users_skype_id'])?$user_model['tj_users_skype_id']:"Not Mentioned"; ?></td></tr>
						  </tbody>
						</table>
					</div>
				</div>
				<?php
				  if(!empty($cover_letters)){
					echo '<h4 class="profile-view-text"><i class="fa fa-file-o padding-right"></i>Cover Letters </h4><br>';
					   foreach($cover_letters as $key=>$cover_det)
					   {
						   echo '<h4 class="tj-register-head">'.$cover_det['tj_cover_letter_name'].'</h4>
								<div class="profile-synopsis">
									<div class="cover-letter-discription" style="word-wrap: break-word;">'.nl2br($cover_det['tj_cover_letter_content']).'
										<br><br>
										<h6 class="pull-right">Last updated on: '.date("F jS, Y",strtotime($cover_det['tj_cover_letter_modified_date'])).'</h6><br><br>
									</div>
								</div>';
					   }					  
				  }
					$tempfile = yii::$app->params['jobseeker_path'].DIRECTORY_SEPARATOR.Url::to($user_model->tj_users_resume_path);
					$name = basename($tempfile);
					$command = 'java -jar "'. Yii::$app->basePath.'/parser/ResumeReader.jar" "'.$tempfile.'"';
					exec($command, $result); 
					
					echo '<h4 class="profile-view-text"><i class="fa fa-file-text-o padding-right"></i>Resume</h4><br>
							<div class="profile-synopsis">
								<div class="cover-letter-discription" style="word-wrap: break-word;">'; 
								
						foreach($result as $row){
							$string = str_replace('/', '-', $row);
						 $text = preg_replace("/[^a-zA-Z 0-9-#:.(),@]+/", "", $string );
								echo $text.'<br>';
						}
					 echo '<br><br>
					 
					
					 
						<div class="profile-resume-download">
						<button type="button" class="btn btn-primary">';
						echo Html::a('<i class="fa fa-download padding-right"></i>&nbsp;&nbsp;Download Resume', Url::to(['/search/search/download','id'=>$user_id]),['style'=>'text-decoration: none; color: #fff; font-weight: bold;float:right; font-size:14px;']);
						echo '</button>
					</div><br><br>'; ?>
					</div>
				</div>
  			</div>			
  			<?= Yii::$app->controller->renderPartial('@app/views/layouts/sidemenu_new') ?>
		</div>
	</div>
</section>
<style>
.tj-profile-view .tj-profile-r-view-design{
	border:none !important;
}
.tj-profile-view .tj-profile-view-design{
     border-radius: none !important; 
     border: none !important; 
}
</style>
