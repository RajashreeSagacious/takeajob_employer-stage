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
use yii\helpers\Url;

AppAsset::register($this);
$this->title ='Candidate Search Result - employer.takeajob.com';

$this->registerJsFile(Yii::$app->request->baseUrl.'/js/jquery-ui.js',['depends' => [\yii\web\JqueryAsset::className()]]);
echo '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">';

$search_result_model = new AdvanceSearch();


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
	
	$limit = 4;
	$track_load = 0;

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

<section class="tj-jobsearch">
  <div class="container">
    <div class="row">
    	<div class="col-md-12">
    		<div class="col-md-8"></div>
    		<div class="col-md-2"><button type="button" class="btn btn-list f-right" aria-haspopup="true" aria-expanded="false"><i class="fa fa-list-alt padding-right"></i> List Search</button></div>
    		<div class="col-md-2"><button type="button" class="btn btn-map" aria-haspopup="true" aria-expanded="false"><i class="fa fa-street-view padding-right"></i> Map Search</button></div>
    	</div>
      <div class="col-md-12 tj-jobsearch-design">
        <div class="col-md-12">
          <div class="col-md-9 available-jobs" id = "recently-visited">  
		  <?php  if(empty(Yii::$app->session['latest_cids'])){?>
			<h4 class="register-text"><i class="fa fa-suitcase padding-right"></i>Available Candidates</h4>
				  <div class="div_more_details" >
					<a class="more_details no_details" ><div class=\"full\"><h4 >Well, it looks like there are no result matching your criterias.</h4></div> 
					</a>
				  </div>
		  <?php } ?>
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
						<input type="hidden" value="0" name="status" id="status" />
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
	  <div align="center" class="animation_image" style="height: 45px;"><img style="width: 35px; margin-top: -70px;" src="<?php echo Url::to(['/images/ajax-loader-search.gif']) ?>"></div>
	
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
      .bind( "keydown, keyup", function( event ) {
		  if (!this.value) {				
			  $("#search_keyword").val(null);
			}		 
		 
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
	$("#status").val(1); 
    $('#recently-visited').html('');
  		$.ajax({
			  type: "POST",
			  url: "<?php echo \yii\helpers\Url::toRoute(['/search/search/filter'])?>",
			  data: $( "#advance_search" ).serialize(),
			  success: function( response ) {
						 $('#recently-visited').append(response);				
			  }
		});	
     }			
  $( document ).on( "change", "input[type='checkbox'], select", showValues ); 
  </script>
  
  <?php 

if(!empty(Yii::$app->session['latest_cids'])){
 $user_ids = implode(', ',Yii::$app->session['latest_cids']);
 // $limit = 2;
 $no_of_loads =  ceil(count(Yii::$app->session['latest_cids'])/($limit)); ?>
<script type="text/javascript">
$(document).ready(function() {
	var track_load = '<?php echo $track_load; ?>'; //total loaded record group(s)
	var loading  = false; //to prevents multipal ajax loads
	var total_groups = <?php echo $no_of_loads; ?>; //total record group(s)
	var user_ids = '<?php echo $user_ids; ?>'; //total record group(s)
	var limit = '<?php echo $limit; ?>'; //total record group(s)
	
	//$('.animation_image').show(); //show loading image
	$('#recently-visited').append('<h4 class="register-text"><i class="fa fa-suitcase padding-right"></i>Available Candidates</h4>');				
	$('#recently-visited').load("<?php echo Url::to(['/search/search/loadmore']) ?>", {'group_no':track_load,'user_ids':user_ids,'limit':limit}, function() {track_load++; }); //load first group
	
	$(window).scroll(function() { //detect page scroll		
		
		 if($(window).scrollTop() + $(window).height() > $(document).height() - 200)   //user scrolled to bottom of the page
		 {
			
			if(track_load <= total_groups && loading==false && ($("#status").val() != 1)) //there's more data to load
			{
				$('.animation_image').show(); //show loading image
				loading = true; //prevent further ajax loading
				
				//load data from the server using a HTTP POST request
				$.post("<?php echo Url::to(['/search/search/loadmore']) ?>",{'group_no': track_load,'user_ids':user_ids,'limit':limit}, function(data){
					$('#recently-visited').append(data);              
					
					track_load++; //loaded group increment
					loading = false;					
					$('.animation_image').hide(); //hide loading image once data is received
				
				}).fail(function(xhr, ajaxOptions, thrownError) { //any errors?
					
					alert(thrownError); //alert with HTTP error
					$('.animation_image').hide(); //hide loading image
					loading = false;
				
				});
				
			}
		}
	});
});
</script>
<?php }?>
<?php /* $post_data = (isset($_POST))?implode(',',$_POST):"";?>
  <script type="text/javascript">
		$(document).ready(function() {
			 var loading = true;
			 var limit = '<?php echo $limit; ?>';
			 var track_load = '<?php echo $track_load; ?>';
			 var post_data = '<?php echo $post_data; ?>';
			$(window).scroll(function() { //detect page scroll			
			if($("#status").val() == 1){		         
				
				 if($(window).scrollTop() + $(window).height() > $(document).height() - 100)
					{
						
						$.post("<?php echo Url::to(['/search/search/filter']) ?>",{'group_no': track_load,'post_data':post_data,'limit':limit}, function(data){
								$('#recently-visited').append(data);				
							   // console.log(data);
							
								//hide loading image
								$('.animation_image').hide(); //hide loading image once data is received
								
								track_load++; //loaded group increment
								loading = false;
								
							
							})
					
					}
				}
			});
		});
</script>
<?php */?>
<style>
.animation_image {width: 866px; background: #ffffff none repeat scroll 0 0;}
</style>