<?php

namespace app\modules\search\controllers;
use Yii;
use yii\db\Query;
use yii\web\Controller;
use yii\helpers\ArrayHelper;
use app\models\UserLoggedHistory;
use app\models\Graduation_Specialization;
class SearchController extends Controller
{
	
    public function actionIndex()
    {
		//$this->layout = 'search_layout';
        return $this->render('index');
    }
		public function actionAdvanced_search()
    {
		$search_model = new AdvanceSearch();		
		if($search_model->load(Yii::$app->request->post())){
		  //echo "<pre>";print_r($search_model);exit;
				$jobseeker_id = array();
				$query = new Query;
				
				$candidates_active_from = date('Y-m-d', strtotime('-'.$search_model->cadidate_active_state.' days'));				
				$query = User::find();
				$query->select('tj_users.tj_id');
				$query->joinWith(['tjloggerhistories','skillresult','userprofile','employerordesignation','usersqualification','usersmoremetails']);
				if(!empty($search_model->any_of_keywords)){
					$any_keywords = explode(',', rtrim($search_model->any_of_keywords,','));
					$query->andWhere(array('LIKE','tj_it_skills.tj_skill_names',$any_keywords));
				} 
				
				
				if(!empty($search_model->search_in))
					$query->andWhere('LIKE','tj_snapshot.tj_snap_resume_headline',$search_model->search_in);
				
				if(!empty($search_model->experience_from))
					$query->andWhere('tj_users.tj_users_experience between '.$search_model->experience_from.' AND '.$search_model->experience_to);
					
				
				if(!empty($search_model->annual_salary_lakhs_from))
					$query->andWhere('tj_snapshot.tj_snap_annual_slary_lakhs between '.$search_model->annual_salary_lakhs_from.' AND '.$search_model->annual_salary_lakhs_to);					
				
				if(!empty($search_model->tj_users_location))
					$query->andWhere(array('IN','tj_users.tj_users_location',$search_model->tj_users_location));
				
				if(!empty($search_model->tj_snap_prefered_location)){
					$pref_loc = $search_model->tj_snap_prefered_location;
					for($i=0;$i<count($pref_loc);$i++)
					($search_model->current_or_preferred == 0)?$query->orWhere("concat(',', tj_snapshot.tj_snap_prefered_location, ',') LIKE '%,$pref_loc[$i],%'"):$query->orWhere("concat(',', tj_snapshot.tj_snap_prefered_location, ',') LIKE '%,$pref_loc[$i],%'");
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
					$designation = explode(',',rtrim($search_model->designation,','));
					$query->orWhere(array('LIKE','tj_employer_or_designation.tj_employer_designation',$designation));
					//$query->andWhere(array('LIKE','tj_employer_or_designation.tj_employer_designation',$designation));
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
				// print_r($search_model->notice_period);exit;
				 if(!empty($search_model->notice_period))
					$query->andWhere(array('IN','tj_employer_or_designation.tj_employer_notice',$search_model->notice_period)); 
					//echo "gujjk";exit;
				
				if(!empty($search_model->category))
					$query->andWhere([array('IN','tj_users_more_details.tj_users_mdetails_categories',$search_model->category)]);
				
				if(!empty($search_model->show_candidate)){
					$show_candidate = implode(',',$search_model->show_candidate);
					$query->andWhere(['LIKE','tj_users_more_details.tj_users_mdetails_employment_type',$search_model->show_candidate_seeking]);					
				}
				
			    $query->andWhere(['tj_users_more_details.tj_users_mdetails_job_type' => $search_model->show_candidate_seeking]); 				
				$jobseeker_id = $query->andWhere(['>=', 'tj_logger_history.tj_logged_in_at', $candidates_active_from])->asArray()->all(); 
				//echo "<pre>";print_r($jobseeker_id);exit;
				
				foreach($jobseeker_id as $ids)
					$jobseeker_ids[] = $ids['tj_id'];			   
						 
			   Yii::$app->session['jobseeker_ids'] = $jobseeker_ids;
			   $this->redirect('search_result');
		
		}
        
	  
        return $this->render('advanced_search',['search_model' => $search_model]);
    }
	
   public function actionSearch_result()
	 {	
		return $this->render('search_result');	
	 }
	
	public function actionGetspecialization($course_id)
	 {
		
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
}
