<?php

namespace app\modules\search\models;

use Yii;
use yii\base\Model;



/**
 * ContactForm is the model behind the contact form.
 */
class AdvanceSearch extends Model
{
    public $annual_salary_lakhs_from;
	public $annual_salary_thousand_from;
	public $annual_salary_lakhs_to;
	public $annual_salary_thousand_to;
	public $tj_users_location;
	public $tj_snap_prefered_location;
	public $any_of_keywords;
	public $all_of_keywords;
	public $excluding_of_keywords;
	public $search_in;
	public $experience_from;
	public $experience_to;
	public $current_or_preferred;
	public $functional_area;
	public $industry_type;
	public $employers;
	public $emp_type;
	public $keyword_type;
	public $exclude_employers;
	public $exclude_emp_type;
	public $exclude_keyword_type;
	public $designation;
	public $designation_emp_type;
	public $designation_keyword_type;
	public $ug_institute_name;
	public $ug_institute_keyword_type;
	public $ug_education_experience_from;
	public $ug_education_experience_to;
	public $ug_education_details;
	public $pg_institute_name;
	public $pg_institute_keyword_type;
	public $pg_education_experience_from;
	public $pg_education_experience_to;
	public $pg_education_details;
	public $doctorate_institute_name;
	public $doctorate_institute_keyword_type;
	public $doctorate_education_experience_from;
	public $doctorate_education_experience_to;
	public $category;
	public $candidate_type;
	public $from_age;
	public $to_age;
	public $cadidate_active_state;
	public $work_status;
	public $other_countries;
	public $show;
	public $show_candidate_seeking;
	public $show_candidate;
	public $notice_period;
	public $sort_by;
	public $resume_per_page;
	public $resume_types;
	public $show_candidtaes_with;
	public $graduation;
	public $graduation_courses;
	public $graduation_specialization;
	public $boolean_value;
	public $boolean_search_type;
	public $role;
	public $post_graduation;
	public $post_graduation_specialization;
	public $doctorate;
	public $doctorate_specialization;
	public $search_skills;
	public $ez_search_field;
	public $search_keyword;
	public $search_by_name;
	public $search_by_phone_no;
	public $skill_keyword;
	public $experience_year;
	public $minim_salary;
	public $maxim_salary;
	public $job_type;
	public $location;
	public $selected_role;
	public $all_location;
	public $search_location;
	public $selected_designations;
	public $selected_roles;
	/* public $functional_area;
	public $role; */
	
	/* current */
   



    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['selected_roles','selected_designations','search_location','all_location','selected_role','location','job_type','maxim_salary','minim_salary','experience_year','skill_keyword','search_by_name','search_by_phone_no','search_keyword','ez_search_field','graduation_specialization','search_skills','annual_salary_lakhs_from','annual_salary_thousand_from','annual_salary_lakhs_to','annual_salary_thousand_to','tj_users_location','tj_snap_prefered_location','any_of_keywords','all_of_keywords','excluding_of_keywords','search_in','experience_from','experience_to','current_or_preferred','functional_area','industry_type','employers','emp_type','keyword_type','exclude_employers','exclude_emp_type','exclude_keyword_type','designation','designation_emp_type','designation_keyword_type','ug_institute_name','ug_institute_keyword_type','ug_education_experience_from','ug_education_experience_to','ug_education_details','pg_institute_name','pg_institute_keyword_type','pg_education_experience_from','pg_education_experience_to','pg_education_details','doctorate_institute_name','doctorate_institute_keyword_type','doctorate_education_experience_from','doctorate_education_experience_to','category','candidate_type','from_age','to_age','cadidate_active_state','work_status','other_countries','show','show_candidate_seeking','show_candidate','notice_period','sort_by','resume_per_page','resume_types','show_candidtaes_with','graduation_courses','graduation_specialization','boolean_value','boolean_search_type','role','post_graduation','post_graduation_specialization','doctorate','doctorate_specialization'], 'safe'],
	['experience_From', 'compare', 'compareValue' => $this->experience_from, 'operator' => '>='],
/* 
    ['experience_to', 'required', 'when' => function($model) {
        return $model->experience_from >= 0;
    }], */

 //['experience_to', 'validateExperience'],
	
 
// ['experience_from', 'required','skipOnEmpty'=>true, 'when' => function($model) {
				// return $this->experience_to < $this->experience_from;
			// }],
/* [['experience_from', 'experience_to'], 'required'],
       
        [
            ['experience_from'], 
            'compare', 
            'compareAttribute'=>'experience_to', 
            'operator'=>'<=', 
            'skipOnEmpty'=>true
        ],
        [
            ['experience_to'], 
            'compare', 
            'compareAttribute'=>'experience_from', 
            'operator'=>'>='
        ], */
					
			/* [['experience_to'], 'compare', 'compareAttribute' => 'experience_from','operator'=>'<','message'=>'Experience To should be greater then experience From.', 'skipOnEmpty'=>true],
			[['experience_to'], 'compare', 'compareAttribute' => 'experience_from','operator'=>'<','message'=>'Experience To should be greater then experience From.', 'skipOnEmpty'=>true], */
			
        ];
    }
	
	
	
	
	
	
	

    /**
     * @return array customized attribute labels
     */
    public function attributeLabels()
    {
        return [
			/* 'employer_designation' => 'Designation',
			'annual_salary_thousand'=>'Thousands',
			'annual_salary_lakhs'=>'Lakhs', */
        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     * @param  string  $email the target email address
     * @return boolean whether the model passes validation
     */
}
