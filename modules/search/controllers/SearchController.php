<?php

namespace app\modules\search\controllers;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use app\modules\search\models\AdvanceSearch;
use app\models\User;
use app\models\EmpCvHistory;
use app\models\UserLoggedHistory;
use app\models\Graduation_Specialization;
use app\models\JobSubCategory;
use app\models\NoticePeriod;

use yii\web\UploadedFile;
use app\models\UserContact;
use app\models\Experience;
use app\models\TjJobCategory;
use app\models\Skills;
use app\models\TjDistrict;
use app\models\Education;
use app\models\TjSnapshot;
use app\models\UsersProjectsRole;
use app\models\TjIndustry; 			
use app\models\UserLogin;
use app\models\Summary;   
use app\models\EmployerOrDesignation;
use app\models\ItSkills;
use app\models\TjUsersKnownLanguages;   
use app\models\TjLanguages;		
use app\models\UserProjects;				
use app\models\UsersQualification;
use app\models\UserSnapshot;  		 
use app\models\CoverLetter;
use app\models\UserPhoto;
use app\models\UserRegistartion;
use app\models\CompleteProfileForm;
use app\models\UserOtpValidation;
use yii\db\Expression;
use app\models\UsersProfilePhoto;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\richweber\twitter\Twitter;
use app\models\JobAlerts;

class SearchController extends Controller
{
	
    public function actionIndex()
    {
		//$this->layout = 'search_layout';
        return $this->render('index');
    }
	public function actionAdvanced_search()
    {
		 if (\Yii::$app->user->isGuest) {
			return $this->redirect(['../dashboard/default/index']);
        } 
		$search_model = new AdvanceSearch();		
		if($search_model->load(Yii::$app->request->post())){
		 // echo "<pre>";print_r($search_model);exit;
				$frequent_cids = array();
				$latest_cids = array();		  
				$jobseeker_id = array();
				
				$query = new Query;				
				$candidates_active_from = date('Y-m-d', strtotime('-'.$search_model->cadidate_active_state.' days'));	
				
				$query = User::find();
				$query->select('tj_users.tj_id');
				$query->joinWith(['tjloggerhistories','skillresult','userprofile','employerordesignation','usersqualification','usersmoremetails','blockcompany']);
				
				if(!empty($search_model->any_of_keywords)){
					$any_keywords = explode(',', rtrim($search_model->any_of_keywords,','));
					$query->andWhere(array('LIKE','tj_it_skills.tj_skill_names',$any_keywords));
				} 				
				
							
				if($search_model->experience_from != "")
					$query->andWhere('tj_users.tj_users_experience between '.$search_model->experience_from.' AND '.$search_model->experience_to);					
				
				if($search_model->annual_salary_lakhs_from != "")
					$query->andWhere('tj_snapshot.tj_snap_annual_slary_lakhs between '.$search_model->annual_salary_lakhs_from.' AND '.$search_model->annual_salary_lakhs_to);				
				
				if(!empty($search_model->tj_users_location))
					$query->andWhere(array('IN','tj_users.tj_users_location',$search_model->tj_users_location));
				
				if(!empty($search_model->tj_snap_prefered_location)){
					$pref_loc = $search_model->tj_snap_prefered_location;
					
					for($i=0;$i<count($pref_loc);$i++)
						($search_model->current_or_preferred == 0)?$query->andWhere("concat(',', tj_snapshot.tj_snap_prefered_location, ',') LIKE '%,$pref_loc[$i],%'"):$query->orWhere("concat(',', tj_snapshot.tj_snap_prefered_location, ',') LIKE '%,$pref_loc[$i],%'");
				}
				
				if(!empty($search_model->industry_type))
					$query->andWhere(array('IN','tj_snapshot.tj_snap_industry',$search_model->industry_type));

				if(!empty($search_model->functional_area))
					$query->andWhere(array('IN','tj_snapshot.tj_snap_functional_area',$search_model->functional_area));
				
				if(!empty($search_model->role))
					$query->andWhere(array('IN','tj_snapshot.tj_snap_role',$search_model->role));
				
				if(!empty($search_model->employers)){	
					$employeers = explode(',',rtrim($search_model->employers,','));
					$query->orWhere(array('LIKE','tj_employer_or_designation.tj_employer_name',$employeers));
					
					if($search_model->emp_type == 1 || $search_model->emp_type == 2)
					    $query->andWhere(['tj_employer_or_designation.tj_employer_status_of_employer' => $search_model->emp_type]);					
				}				
			
			
				if(!empty($search_model->designation)){
					$designations = explode(',',$search_model->designation);
					 $query->orWhere(array('IN','tj_employer_or_designation.tj_employer_designation',$designations));
					
					if($search_model->designation_emp_type == 1 || $search_model->designation_emp_type == 2)
					      $query->andWhere(['tj_employer_or_designation.tj_employer_status_of_employer' => $search_model->designation_emp_type]);					
				}
				$search_model->ug_education_experience_from = $search_model->pg_education_experience_from = 2000;
				$search_model->ug_education_experience_to = $search_model->pg_education_experience_to = 2015;
				
				if(!empty($search_model->graduation_courses)){
					
					for($g=0;$g<count($search_model->graduation_courses);$g++){
						
						$query->orWhere(['tj_users_qualification.tj_uq_qualification' => $search_model->graduation_courses[$g]]);
						if(!empty($search_model->graduation_specialization))
							$query->andWhere(array('IN','tj_users_qualification.tj_uq_specialization',$search_model->graduation_specialization));
						if(!empty($search_model->ug_institute_name))
							$query->andWhere(array('IN','tj_users_qualification.tj_uq_institute',$search_model->ug_institute_name));

						if(!empty($search_model->ug_education_experience_from)){							
							 $query->andWhere(['>=', 'YEAR(tj_users_qualification.tj_uq_duration_start)', $search_model->ug_education_experience_from]);
							 $query->andWhere(['<=', 'YEAR(tj_users_qualification.tj_uq_duration_end)', $search_model->ug_education_experience_to]);
							}
					  $query->andWhere(['tj_users_qualification.tj_uq_status'=>1]);	
					}
				}
				
				
				if(!empty($search_model->post_graduation)){
					
					for($pg=0;$pg<count($search_model->post_graduation);$pg++){						
						$query->orWhere(['tj_users_qualification.tj_uq_qualification' => $search_model->post_graduation[$pg]]);
						
						if(!empty($search_model->post_graduation_specialization))
							$query->andWhere(array('IN','tj_users_qualification.tj_uq_specialization',$search_model->post_graduation_specialization));
						
						if(!empty($search_model->pg_institute_name))
							$query->andWhere(array('IN','tj_users_qualification.tj_uq_institute',$search_model->pg_institute_name));						

						if(!empty($search_model->pg_education_experience_from)){
							
							 $query->andWhere(['>=', 'YEAR(tj_users_qualification.tj_uq_duration_start)', $search_model->pg_education_experience_from]);
							 $query->andWhere(['<=', 'YEAR(tj_users_qualification.tj_uq_duration_end)', $search_model->pg_education_experience_to]);
						 }
					 $query->andWhere(['tj_users_qualification.tj_uq_status'=>2]);
					}
				}
				
				if(!empty($search_model->doctorate)){
					
					for($dct=0;$dct<count($search_model->doctorate);$dct++){						
						$query->orWhere(['tj_users_qualification.tj_uq_qualification' => $search_model->doctorate[$dct]]);
						
						if(!empty($search_model->doctorate_specialization))
							$query->andWhere(array('IN','tj_users_qualification.tj_uq_specialization',$search_model->doctorate_specialization));
						
						if(!empty($search_model->doctorate_institute_name))
							$query->andWhere(array('IN','tj_users_qualification.tj_uq_institute',$search_model->doctorate_institute_name));						

						if(!empty($search_model->doctorate_education_experience_from)){							
							 $query->andWhere(['>=', 'YEAR(tj_users_qualification.tj_uq_duration_start)', $search_model->doctorate_education_experience_from]);
							 $query->andWhere(['<=', 'YEAR(tj_users_qualification.tj_uq_duration_end)', $search_model->doctorate_education_experience_to]);
						 }
					 $query->andWhere(['tj_users_qualification.tj_uq_status1'=>3]);
					}
				}
				
				 if(!empty($search_model->notice_period))
					$query->andWhere(array('IN','tj_employer_or_designation.tj_employer_notice',$search_model->notice_period)); 					
				
				if(!empty($search_model->category))
					$query->andWhere([array('IN','tj_users_more_details.tj_users_mdetails_categories',$search_model->category)]);
				
				if(!empty($search_model->show_candidate)){
					$show_candidate = implode(',',$search_model->show_candidate);
					$query->andWhere(['LIKE','tj_users_more_details.tj_users_mdetails_employment_type',$show_candidate]);					
				}
				if($search_model->show_candidate_seeking != '0,1')
						$query->andWhere(['tj_users_more_details.tj_users_mdetails_job_type' => $search_model->show_candidate_seeking]); 
				
				$cmp_id =  Yii::$app->user->identity->tj_emp_company_id;	
				$query->andWhere(['tj_users.tj_users_visibility_settings' => 1]);
				// $query->andWhere("concat(',', tj_block_company.tj_bcompany_company_id, ',') NOT LIKE '%,$cmp_id,%'");
				/* $query->andWhere(['NOT LIKE','tj_block_company.tj_bcompany_company_id' , $cmp_id]);
				echo "<pre>";print_r($jobseeker_id);exit; */
				
				$jobseeker_id = $query->andWhere(['>=', 'tj_logger_history.tj_logged_in_at', $candidates_active_from])->asArray()->all(); 
				
				foreach($jobseeker_id as $ids)
					$latest_cids[] = $ids['tj_id'];	

				
				$frequently_visited_ids = EmpCvHistory::find()->select(['tj_emp_cv_history.tj_emp_job_seeker_id','COUNT(tj_emp_cv_history.tj_emp_job_seeker_id) AS countid']) ->join('LEFT JOIN', 'tj_users', 'tj_users.tj_id=tj_emp_cv_history.tj_emp_job_seeker_id')->where(array('IN','tj_emp_cv_history.tj_emp_job_seeker_id',$latest_cids))->groupBy('tj_emp_cv_history.tj_emp_job_seeker_id')->orderBy(['countid' => SORT_DESC])->all();			
			
			
			   foreach($frequently_visited_ids as $frequently_visited_id)
				  $frequent_cids[] = $frequently_visited_id['tj_emp_job_seeker_id'];
						 
			   Yii::$app->session['latest_cids'] = $latest_cids;
			   Yii::$app->session['frequent_cids'] = $frequent_cids;
			   $this->redirect('search_result');
		
		}
        
	  
        return $this->render('advanced_search',['search_model' => $search_model]);
    }
	
   public function actionSearch_result(){
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['../dashboard/default/index']);
        } 
		//echo $user = Yii::$app->user->identity;exit;
		return $this->render('search_result');	
	 }
	
	
	public function actionGetspecialization($course_id){
		
		$course_ids = array();
		$specialization = array();
		$specializations = array();
		 if(!empty($course_id))
			{
				$course_ids = explode(',',$course_id);
				foreach($course_ids as $key=>$id){
					$special = Graduation_Specialization::find()->where(['tj_es_education_id'=>$id])->orderBy('tj_es_name ASC')->all();
					array_push($specialization,ArrayHelper::map($special, 'tj_es_id', 'tj_es_name'));
				}
				foreach($specialization as $k=>$v){
					foreach($v as $i=>$j)
					   $specializations[$i] = $j;
					
				}
				$course_specialization = array_unique($specializations);
				ksort($course_specialization);				
				Yii::$app->response->format = 'json';
				return $course_specialization;
			}  
	}
	
	public function actionEasy_search(){
		if (\Yii::$app->user->isGuest) {
			return $this->redirect(['../dashboard/default/index']);
        } 
		
		
		
		$frequent_cids = array();
		$latest_cids = array();
		$jobseeker_id = array();
		
		$ez_search_model = new AdvanceSearch();		
		if($ez_search_model->load(Yii::$app->request->post())){ 
		
			   $jobseeker_id = array();
			   $skill_ids = array();
			   $designation_ids = array();
			   $query = new Query;				
			   $candidates_active_from = date('Y-m-d', strtotime('-'.$ez_search_model->cadidate_active_state.' days'));	
				
			   $query = User::find();
			   $query->select('tj_users.tj_id');
			   $query->joinWith(['tjloggerhistories','skillresult','userprofile','employerordesignation','blockcompany']);
			
			if(isset($ez_search_model->ez_search_field)){
				$ez_search_field = explode(',',$ez_search_model->ez_search_field);
				foreach($ez_search_field as $keywords){
					
					if(strpos($keywords,'years') || strpos($keywords,'yrs') !== FALSE){
					    $exp_years = explode('-',$keywords);
						$from_year = ($exp_years[0] == 0)?$exp_years[0]+1:$exp_years[0]+1;
						$to = explode('y',strtolower($exp_years[1]));
						$to_year = mysql_real_escape_string($to[0])+1;
						$query->andWhere('tj_users.tj_users_experience between '.$from_year.' AND '.$to_year);
						
					}

				
					if(strpos($keywords,'lakhs')!==FALSE){
						$sal_lakhs = explode('-',$keywords);
						$to_sal = explode('l',strtolower($sal_lakhs[1]));
						$query->andWhere('tj_snapshot.tj_snap_annual_slary_lakhs between '.$sal_lakhs[0].' AND '.mysql_real_escape_string($to_sal[0]));
					}
					
		       }
			}
			
			if(isset($ez_search_model->search_keyword)){
			    $search_keyword = explode(',',$ez_search_model->search_keyword);
				foreach($search_keyword as $keyword){
				
					if(strpos($keyword,'0-')!==FALSE){
						$skill_keyword = explode('-',$keyword);
    					$query->andWhere(['LIKE','tj_it_skills.tj_skill_names',$skill_keyword[1]]); 				
					}											
				
					if(strpos($keyword,'2-')!==FALSE){
						$loc_keyword = explode('-',$keyword);
						$query->andWhere(['tj_users.tj_users_location' => $loc_keyword[1]]);							
					}
						
					  if(strpos($keyword,'1-')!==FALSE){
						$disig_keyword = explode('-',$keyword);
					   $query->andWhere(['IN','tj_employer_or_designation.tj_employer_designation',$disig_keyword[1]]);	 				
					}  
			  }
		  }
		    $cmp_id =  Yii::$app->user->identity->tj_emp_company_id;	
		    $query->andWhere(['tj_users.tj_users_visibility_settings' => 1]);
		  
		  //  $query->andWhere(['NOT LIKE','tj_block_company.tj_bcompany_company_id' , $cmp_id]);
		/*  $query->andWhere("concat(',', tj_block_company.tj_bcompany_company_id, ',') NOT LIKE '%,$cmp_id,%'");
			$query->orWhere('tj_block_company.tj_bcompany_company_id is null');  */
		
			
			$jobseeker_id = $query->andWhere(['>=', 'tj_logger_history.tj_logged_in_at', $candidates_active_from])->asArray()->all(); 
			foreach($jobseeker_id as $ids)
				$latest_cids[] = $ids['tj_id'];	
				//echo "<pre>";print_r($latest_cids);exit;
			
	
			$frequently_visited_ids = EmpCvHistory::find()->select(['tj_emp_cv_history.tj_emp_job_seeker_id','COUNT(tj_emp_cv_history.tj_emp_job_seeker_id) AS countid']) ->join('LEFT JOIN', 'tj_users', 'tj_users.tj_id=tj_emp_cv_history.tj_emp_job_seeker_id')->where(array('IN','tj_emp_cv_history.tj_emp_job_seeker_id',$latest_cids))->groupBy('tj_emp_cv_history.tj_emp_job_seeker_id')->orderBy(['countid' => SORT_DESC])->all();			
			
			
			foreach($frequently_visited_ids as $frequently_visited_id)
				$frequent_cids[] = $frequently_visited_id['tj_emp_job_seeker_id'];
			
			//echo "<pre>";print_r($frequent_cids);exit;
						 
		   Yii::$app->session['latest_cids'] = $latest_cids;
		   Yii::$app->session['frequent_cids'] = $frequent_cids;
		   $this->redirect('search_result'); 
			
	   } 		
		return $this->render('easy_search',['ez_search_model' => $ez_search_model]);
	}
	
	
	public function actionView_candidate_profile(){
		
		  if (\Yii::$app->user->isGuest) {
			return $this->redirect(['../dashboard/default/index']);
        } 
//echo "<pre>";print_r(Yii::$app->user->identity);exit;		
		$user_id = $_GET['cid'];
		
		
		$view_jobs_model = new EmpCvHistory();
		$view_jobs_model->tj_emp_company_id = Yii::$app->user->identity->tj_emp_company_id;
		$view_jobs_model->tj_emp_recruiter_id = Yii::$app->user->identity->tj_emp_recruiter_id;
		$view_jobs_model->tj_emp_job_seeker_id = $user_id;
		$view_jobs_model->tj_emp_history_type = 1;
		$view_jobs_model->save();
		
	    if(\Yii::$app->user->isGuest){
            $model = new UserLogin();
            return $this->redirect(Yii::$app->homeUrl.'site/login',$model);
        } 
       
		 
		$profile_pic_model = new CompleteProfileForm();
		$data['profile_pic_model'] = $profile_pic_model;
			if($_POST){
				$profile_pic_model->photo = UploadedFile::getInstance($profile_pic_model, 'photo');
					if(isset($profile_pic_model->photo) && !empty($profile_pic_model->photo))
						$profile_pic_model->update_profile_photo();
			
			}
        $data['image_location'] = '@web/images/avatar.png';
        $data['user_prefered_location'] = "Not Mentioned";
        $data['job_category'] = "Not Mentioned";
        $data['experience'] = "Not Mentioned";
        $data['user_location'] = "Not Mentioned";
        $data['user_skill_all'] = "Not Mentioned";
        $data['user_education'] = "Not Mentioned";
        $data['job_category'] = "Not Mentioned";
        $data['job_role'] = "Not Mentioned";
        $data['user_industry'] = "Not Mentioned";
        
        
		$user_model = User::findOne($user_id);
        $data['user_model'] = $user_model;
        
        $user_snapshot = UserSnapshot::findOne(['tj_snap_user_id' => $user_id]);
		if(empty($user_snapshot)){
			
			if($user_model->tj_users_profile_status == 0)
                   $this->redirect(\Yii::$app->urlManager->createUrl("userprofile/profile"));
            else if($user_model->tj_users_profile_status == 1)
                   $this->redirect(\Yii::$app->urlManager->createUrl("userprofile/profile_two"));
			else
				echo "Please fill Required details of Profile and profile_two completely ....";exit;
		 }
        $data['user_snapshot'] = $user_snapshot;
        
        $user_profilephoto = UsersProfilePhoto::findOne(['tj_upp_user_id' => $user_id]);        
        if(isset($user_profilephoto))
        	$data['image_location'] = $user_profilephoto->tj_upp_path_medium;
        
        

        
        if($user_snapshot->tj_snap_prefered_location){
        	$user_location = TjDistrict::findOne($user_snapshot->tj_snap_prefered_location);
        	$data['user_prefered_location'] = $user_location -> tj_dt_districtname;
        }
		
		if($user_snapshot->tj_snap_prefered_location){
        	$user_location = TjDistrict::findOne($user_snapshot->tj_snap_prefered_location);
        	$data['user_prefered_location'] = $user_location -> tj_dt_districtname;
        }
        
        if(isset($user_model -> tj_users_location)){
        	$locationData = TjDistrict::findOne($user_model -> tj_users_location);
        	$data['user_location'] = $locationData -> tj_dt_districtname;
        }
		
		if(isset($user_model -> tj_users_city)){
        	$locationData = TjDistrict::findOne($user_model -> tj_users_city);
        	$data['users_city'] = $locationData -> tj_dt_districtname;
        }
        
        $experienceData = Experience::findOne($user_model -> tj_users_experience);
        $data['experience'] = $experienceData->tj_exp_years;
        
	    $skillArray = explode(',',$user_model -> tj_users_skills);
        foreach($skillArray as $key => $val){
        	$skillData = Skills::findOne([$val]);
        	$skills[] = $skillData -> tj_sk_name;
        }
        $data['skills'] = $skills;
        $data['user_skill_all'] = implode(',',$skills);        
       
        $educationData = Education::findOne($user_model -> tj_users_qualification);
        $data['user_education'] = $educationData -> tj_edu_title;        
        
        
        if($user_snapshot -> tj_snap_functional_area){
	        $categoryData = TjJobCategory::findOne($user_snapshot -> tj_snap_functional_area);
	        $data['job_category'] = $categoryData -> tj_jc_name;
        }
        
        if($user_snapshot -> tj_snap_role){
          $roleData = JobSubCategory::findOne($user_snapshot -> tj_snap_role);
          $data['job_role'] = $roleData -> tj_jsc_name;
        }
        
        if($user_snapshot -> tj_snap_industry){
	        $industryData = TjIndustry::findOne($user_snapshot -> tj_snap_industry);
	        $data['user_industry'] = $industryData -> tj_ind_name;
        }        
        
        $summaryData = Summary::findOne(['tj_summary_user_id' => $user_id]);        
        $data['summary_content'] = ($summaryData)?$summaryData -> tj_summary_content:"";
        
        $data['employer_data'] = EmployerOrDesignation::find()->where(['tj_employer_user_id' => $user_id])->orderBy("tj_employer_status_of_employer")->all(); 
        $data['current_employer_data'] = EmployerOrDesignation::find()->where(['tj_employer_user_id' => $user_id])->andWhere(['tj_employer_status_of_employer' => 1])->one();
		
		$data['designation'] = "";
		if(!empty($data['current_employer_data'])){
          $desig_data = JobSubCategory::findOne($data['current_employer_data']->tj_employer_designation);
          $data['designation'] = $desig_data -> tj_jsc_name;
        }
	
        $data['it_skills_data'] = ItSkills::findAll(['tj_it_skill_user_id' => $user_id]);        
        $data['Known_languages'] = TjUsersKnownLanguages::findAll(['tj_users_klang_user_id' => $user_id]);  
        $data['user_projects'] = UserProjects::findAll(['tj_uprj_users_id' => $user_id]);
        $data['user_qualification'] = UsersQualification::find()->where(['tj_uq_users_id' => $user_id])->orderBy('tj_uq_status DESC')->all();
       
        $data['cover_letters'] = CoverLetter::find()->where(['tj_cover_letter_user_id' => $user_id])->orderBy("tj_cover_letter_modified_date DESC")->all();
		
	
       return $this->render('view_candidate_profile' , $data);
	}
	public function actionSpecial_search(){
		  if (\Yii::$app->user->isGuest) {
			return $this->redirect(['../dashboard/default/index']);
        } 
		$jobseeker_id = array();
		$latest_cids =array();
		$frequent_cids =array();
		   
		
		$special_search_model = new AdvanceSearch();
		if($special_search_model->load(Yii::$app->request->post())){ 
		  $query = new Query;				
			   $candidates_active_from = date('Y-m-d', strtotime('-'.$special_search_model->cadidate_active_state.' days'));	
				
			   $query = User::find();
			   $query->select('tj_users.tj_id');
			   $query->joinWith(['tjloggerhistories','blockcompany']);
			   
			   if(!empty($special_search_model->search_by_name))	   
				  $query->andWhere(['LIKE','CONCAT(tj_users.tj_users_fname, \' \', tj_users.tj_users_lname)',$special_search_model->search_by_name]);
				  
			   if(!empty($special_search_model->search_by_phone_no))
				   $query->andWhere(['LIKE','tj_users.tj_users_phone',$special_search_model->search_by_phone_no]);
			   
			   	$cmp_id =  Yii::$app->user->identity->tj_emp_company_id;	
				$query->andWhere(['tj_users.tj_users_visibility_settings' => 1]);
			    // $query->andWhere("concat(',', tj_block_company.tj_bcompany_company_id, ',') NOT LIKE '%,$cmp_id,%'");
			    $jobseeker_id = $query->andWhere(['>=', 'tj_logger_history.tj_logged_in_at', $candidates_active_from])->asArray()->all(); 
			
			foreach($jobseeker_id as $ids)
				$latest_cids[] = $ids['tj_id'];			   
		
			$frequently_visited_ids = EmpCvHistory::find()->select(['tj_emp_cv_history.tj_emp_job_seeker_id','COUNT(tj_emp_cv_history.tj_emp_job_seeker_id) AS countid']) ->join('LEFT JOIN', 'tj_users', 'tj_users.tj_id=tj_emp_cv_history.tj_emp_job_seeker_id')->where(array('IN','tj_emp_cv_history.tj_emp_job_seeker_id',$latest_cids))->groupBy('tj_emp_cv_history.tj_emp_job_seeker_id')->orderBy(['countid' => SORT_DESC])->all();			
			
			
			foreach($frequently_visited_ids as $frequently_visited_id)
				$frequent_cids[] = $frequently_visited_id['tj_emp_job_seeker_id'];
			
						 
		   Yii::$app->session['latest_cids'] = $latest_cids;
		   Yii::$app->session['frequent_cids'] = $frequent_cids;

		   //echo "<pre>";print_r($frequent_cids);exit; 
		   $this->redirect('search_result'); 
		}
		return $this->render('special_search' , ['special_search_model' => $special_search_model]);
		
	}
	public function actionShiva(){
		   return $this->render('search_result_shiva'); 
		
	}
	public function actionFilter(){
		 /* if(!empty($_REQUEST)){
			echo "<pre>";print_r($_REQUEST);
		} */ 
		$candidate_kills = "NA";
		$notice = "NA";	
		$designation = "NA";
		$company = "NA";
		$result = '<h4 class="register-text"><i class="fa fa-suitcase padding-right"></i>Available Candidates</h4>';
		$jobseeker_id = array();		
		$skill_ids =array();
		$designation_ids =array();
		//$limit = (isset($_REQUEST['limit']))?$_REQUEST['limit']:2;
		//$offset = (isset($_REQUEST['track_load']))?$_REQUEST['track_load']:0;
		$filter_search_model = new AdvanceSearch();	
		if($filter_search_model->load(Yii::$app->request->post())){		
			
			   $query = new Query;				
			   $candidates_active_from = date('Y-m-d', strtotime('-60 days'));	
				
			   $query = User::find();
			   $query->joinWith(['tjloggerhistories','skillresult','usersmoremetails','userprofile','experience', 'jobcategory', 'location', 'profilephoto','blockcompany','employerordesignation']);
			   
			 
			   
			   if($filter_search_model->search_keyword != ""){
				   
					  $skildegs =  explode(',',$filter_search_model->search_keyword);				   
					   foreach($skildegs as $skill_desig){
						   
						  $temp = explode('-', $skill_desig);
						  $temp[0] == 0 ? array_push($skill_ids, $temp[1]) : array_push($designation_ids, $temp[1]);
					   }
					   
					   if(!empty($skill_ids))					
							$query -> orWhere(array('LIKE','tj_it_skills.tj_skill_names',$skill_ids));	

					   if(!empty($designation_ids))					   
						    $query -> orWhere(array('IN','tj_employer_or_designation.tj_employer_designation',$designation_ids));
				 
			   }
			
	    if(($filter_search_model->experience_from != "") && ($filter_search_model->experience_from < $filter_search_model->experience_to))				   
			 $query->andWhere('tj_users.tj_users_experience between '.$filter_search_model->experience_from.' AND '.$filter_search_model->experience_to);
				
		if(($filter_search_model->annual_salary_lakhs_from != "") && ($filter_search_model->annual_salary_lakhs_from < $filter_search_model->annual_salary_lakhs_to))				
			$query->andWhere('tj_snapshot.tj_snap_annual_slary_lakhs between '.$filter_search_model->annual_salary_lakhs_from.' AND '.$filter_search_model->annual_salary_lakhs_to);
			 				
			  if((isset($_POST['job_type'])) && (!empty($_POST['job_type']))){
				   $job_type = (in_array(4,$_POST['job_type']))?"0,1,2,3":implode(',',$_POST['job_type']);
					$query->andWhere(['LIKE','tj_users_more_details.tj_users_mdetails_employment_type',$job_type]);					
				  
			  }
			
			  if((isset($filter_search_model->search_location)) && (!empty($filter_search_model->search_location)) ){
				    $location = explode(',',$filter_search_model->search_location);
					$query -> orWhere(Array('IN','tj_users.tj_users_location',$location));
			  }
				
			$cmp_id =  Yii::$app->user->identity->tj_emp_company_id;	
			$query->andWhere(['tj_users.tj_users_visibility_settings' => 1]);
			//$query->andWhere("concat(',', tj_block_company.tj_bcompany_company_id, ',') NOT LIKE '%,$cmp_id,%'");
//->limit(20)->offset($latest_offset)			
			$jobseekers = $query->andWhere(['>=', 'tj_logger_history.tj_logged_in_at', $candidates_active_from])->all(); 
			
			if(!empty($jobseekers)){
			foreach($jobseekers as $seeker){
				
				$salary = (($seeker->userprofile['tj_snap_annual_slary_lakhs'] != "" || $seeker->userprofile['tj_snap_annual_salary_thousands'] != ""))?'<i class="fa fa-inr"></i>'.$seeker->userprofile['tj_snap_annual_slary_lakhs'].' - '.($seeker->userprofile['tj_snap_annual_slary_lakhs']+1).' LPA':"NA";			
			
				  
				  $user_name = $seeker->tj_users_fname.' '.$seeker->tj_users_lname;
				  
				  $image_location = Yii::$app->getUrlManager()->getBaseUrl().'/images/avatar.png';
				  if(isset($seeker->profilephoto['tj_upp_path_medium']))
						$image_location = yii::$app->params['jobseeker_url'].''.str_replace('@web','',$seeker->profilephoto['tj_upp_path_medium']);
				
				$job_category = EmployerOrDesignation::find()->where(['tj_employer_user_id' => $seeker->tj_id])->andWhere(['tj_employer_status_of_employer' => 1])->One();
			
				if(!empty($job_category)){
					
					$curr_designation = JobSubCategory::findOne($job_category['tj_employer_designation']); 							
					$designation = $curr_designation['tj_jsc_name'];
					$company = $job_category['tj_employer_name'];
					if(isset($job_category['tj_employer_notice'])){					
						$notice = NoticePeriod::findOne($job_category['tj_employer_notice']);
						$notice = $notice['tj_notice'];			
					}
				}
				
				$skills = ItSkills::find()->select("tj_skill_names")->where(["tj_it_skill_user_id" => $seeker->tj_id])->All();
				// ->limit($limit)->offset($offset)
				if(!empty($skills)){
					foreach($skills as $key=>$skill){
						$skill_names[] = $skill['tj_skill_names'];
					}
					$candidate_kills = implode(", ",array_unique($skill_names));
				}
				
				$job_location = TjDistrict::findOne($seeker->tj_users_location);
				$experienceData = Experience::findOne($seeker->tj_users_experience);
				$experience = $experienceData->tj_exp_years;
				
					$result = $result.'<div class="col-md-12 jobs-page"><div class="col-md-2 company-logo"><img src="'.$image_location.'" class="img-responsive img-circle"></div><div class="col-md-10 company-details"><div class="col-md-12 pad-left"><div class="col-md-7 pad-top-left"><h4>'.$user_name.'</h4></div><div class="col-md-5 pad-left"><div class="date-more"><div class="btn-group edit-btn pull-right" role="group" aria-label="..."><a href="'.Yii::$app->homeUrl.''.'search/search/view_candidate_profile?cid='.$seeker->tj_id.'" target="_blank"><button type="button" class="btn btn-default edit-icon"><i class="fa fa-plus right-pad"></i>More</button></a></div></div></div></div><div class="col-md-10 company-about"><p>Current Company : '.$company.'<br/>Current Designation : '.$designation.'<br/>Notice Period : '.$notice.'<br/><br/>Skills : '.$candidate_kills.'</p></div><div class="col-md-12 pad-left"><div class="col-md-3 work-loc"><i class="fa fa-map-marker"></i>'.$job_location['tj_dt_districtname'].'</div><div class="col-md-3 work-exp"><i class="fa fa-graduation-cap"></i>'.$experience.'</div><div class="col-md-3 work-sal"><i class="fa fa-money"></i>'.$salary.'</div><div class="col-md-3 work-date"><i class="fa fa-clock-o"></i>'.date("F jS, Y",strtotime($seeker->tjloggerhistories['tj_logged_in_at'])).'</div><br><br><span id="more_'.$seeker->tj_id.'" ><div class="col-md-9 work-date"><i class="fa fa-envelope-o"></i>'.$seeker->tj_users_email.'</div><div class="col-md-3 work-date"><i class="fa fa-mobile"></i>'.$seeker->tj_users_phone.'</div><span></div></div></div>';

					
			   }	
			}else{
				
				$result = $result.'<div class="div_more_details" >
					<a class="more_details no_details" ><div class=\"full\"><h4 >Well, it looks like there are no more result matching your criterias.</h4></div> 
					</a>
				  </div>';
			}      
	
			return $result;
		}
	}
	
	public function actionRole_search(){
		
		 if(\Yii::$app->user->isGuest) {
			return $this->redirect(['/dashboard/default/index']);
        }

		$jobseeker_id = array();
		$latest_cids =array();
		
		$role_search_model = new AdvanceSearch();
	
		
		if($role_search_model->load(Yii::$app->request->post())){ 
			   $query = new Query;				
			   $candidates_active_from = date('Y-m-d', strtotime('-'.$role_search_model->cadidate_active_state.' days'));	
				
			   $query = User::find();
			   $query->select('tj_users.tj_id');
			   $query->joinWith(['tjloggerhistories','blockcompany','userprofile','employerordesignation']);
			    if(!empty($_POST['selected_roles']))
					$query->andWhere(array('IN','tj_snapshot.tj_snap_functional_area',$_POST['selected_roles']));
				
				if(!empty($_POST['selected_designations']))
					$query->andWhere(array('IN','tj_employer_or_designation.tj_employer_designation',$_POST['selected_designations']));
				
				$cmp_id =  Yii::$app->user->identity->tj_emp_company_id;	
				$query->andWhere(['tj_users.tj_users_visibility_settings' => 1]);
				// $query->andWhere("concat(',', tj_block_company.tj_bcompany_company_id, ',') NOT LIKE '%,$cmp_id,%'");
				$jobseeker_id = $query->andWhere(['>=', 'tj_logger_history.tj_logged_in_at', $candidates_active_from])->asArray()->all(); 
				
				foreach($jobseeker_id as $ids)
					$latest_cids[] = $ids['tj_id'];
				Yii::$app->session['latest_cids'] = $latest_cids;
				$this->redirect('search_result'); 
		}
		
	  return $this->render('role_search',['role_search_model' => $role_search_model]);
	}
	public function actionRole_more(){
		$result = "";
		$JobsubData = JobSubCategory::find()->where(array('IN','tj_jsc_category_id',$_POST['selected_roles']))->orderBy('tj_jsc_name')->all();
		
		foreach($JobsubData as $key=>$des){				
			$result = $result.'<div class="checkbox"><label><input type="checkbox" name="selected_designations[]" value="'.$des['tj_jsc_id'].'">'.$des['tj_jsc_name'].'</label></div>';
		}
		return $result;
	}
	public function actionDownload($id)
    {		
        	$user_model = User::findOne($id);	
			$tempfile = Yii::$app->params['jobseeker_path'].'/'.$user_model->tj_users_resume_path;
			$name = basename($tempfile);
			header('Content-Type: application/force-download');
			header('Content-Disposition: attachment; filename="'.$name.'"'); 
			header('Content-Length: ' . filesize($tempfile));
			header("Content-Transfer-Encoding: binary");
			header('Accept-Ranges: bytes');
			readfile($tempfile);
	}	
	
	public function actionLoadmore(){
		
	 if(!empty($_REQUEST)){
		$index = $_REQUEST['group_no']	* $_REQUEST['limit'];
		$output = array_slice(explode(',',$_REQUEST['user_ids']), $index, $_REQUEST['limit']);
		$latest_visited_cids = User::find()->asArray()->where(array('in', 'tj_id', $output))->all();
		
			    $candidate_kills = "NA";
				$notice = "NA";	
				$designation = "NA";
				$company = "NA";
				$result = '';
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
				if(!empty($job_category)){
					
					$curr_designation = JobSubCategory::findOne($job_category['tj_employer_designation']); 							
					$designation = $curr_designation['tj_jsc_name'];
					$company = $job_category['tj_employer_name'];
					if(isset($job_category['tj_employer_notice'])){					
						$notice = NoticePeriod::findOne($job_category['tj_employer_notice']);
						$notice = $notice['tj_notice'];			
					}
				}
				
			    $job_location = TjDistrict::findOne($latest_visited['tj_users_location']);
				$image_location = Yii::$app->getUrlManager()->getBaseUrl().'/images/avatar.png';
				$user_profilephoto = UsersProfilePhoto::findOne(['tj_upp_user_id' => $latest_visited['tj_id']]); 
				
				if(isset($user_profilephoto))                                                                              
				$image_location = yii::$app->params['jobseeker_url'].''.str_replace('@web','',$user_profilephoto->tj_upp_path_medium);
				  
				$experienceData = Experience::findOne($latest_visited['tj_users_experience']);
				$experience = $experienceData->tj_exp_years;
				  
				$user_snapshot = UserSnapshot::find()->select('tj_snap_annual_slary_lakhs,tj_snap_annual_salary_thousands')->where(['tj_snap_user_id' => $latest_visited['tj_id']])->One();
				 
				$salary = "NA";
				if($user_snapshot)
				$salary = (($user_snapshot -> tj_snap_annual_slary_lakhs != "" || $user_snapshot -> tj_snap_annual_salary_thousands != ""))?'<i class="fa fa-inr"></i>'.$user_snapshot -> tj_snap_annual_slary_lakhs.' - '.($user_snapshot -> tj_snap_annual_slary_lakhs+1).' LPA':"NA";
			
			      $result = $result.'<div class="col-md-12 jobs-page"><div class="col-md-2 company-logo"><img src="'.$image_location.'" class="img-responsive img-circle"></div><div class="col-md-10 company-details"><div class="col-md-12 pad-left"><div class="col-md-7 pad-top-left"><h4>'.$latest_visited['tj_users_fname']." ".$latest_visited['tj_users_lname'].'</h4></div><div class="col-md-5 pad-left"><div class="date-more"><div class="btn-group edit-btn pull-right" role="group" aria-label="..."><a href="'.Yii::$app->homeUrl.''.'search/search/view_candidate_profile?cid='.$latest_visited['tj_id'].'" target="_blank"><button type="button" class="btn btn-default edit-icon"><i class="fa fa-plus right-pad"></i>More</button></a></div></div></div></div><div class="col-md-10 company-about"><p>Current Company : '.$company.'<br/>Current Designation : '.$designation.'<br/>Notice Period : '.$notice.'<br/><br/>Skills : '.$candidate_kills.'</p></div><div class="col-md-12 pad-left"><div class="col-md-3 work-loc"><i class="fa fa-map-marker"></i>'.$job_location['tj_dt_districtname'].'</div><div class="col-md-3 work-exp"><i class="fa fa-graduation-cap"></i>'.$experience.'</div><div class="col-md-3 work-sal"><i class="fa fa-money"></i>'.$salary.'</div><div class="col-md-3 work-date"><i class="fa fa-clock-o"></i>'.date("F jS, Y",strtotime($logged_history['tj_logged_in_at'])).'</div><br><br><span id="more_'.$latest_visited['tj_id'].'" ><div class="col-md-9 work-date"><i class="fa fa-envelope-o"></i>'.$latest_visited['tj_users_email'].'</div><div class="col-md-3 work-date"><i class="fa fa-mobile"></i>'.$latest_visited['tj_users_phone'].'</div><span></div></div></div>';			
			
				}		
		    
			return $result;
		} 
	}
}
