<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use app\models\EmpCompanies;
use app\models\EmpJobs;
use app\models\JobApplied;
use app\models\Company;
use app\models\User;
use yii\helpers\Url;

\app\assets\UserAppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" href="<?php echo Yii::$app->homeUrl; ?>img/fav.png" type="image/x-icon" />
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    
    <?php $this->head() ?>
    
</head>
<body>

<style type="text/css">

.nav > li > a {
    position: relative;
    display: block;
    padding: 10px 15px; color:#9d9d9d!important;
}
.nav > li > a:hover {
   background-color:#333!important;color:#fff!important; text-decoration:none!important;
}

.fot-links a:hover{text-decoration:none!important; color:#888B90!important;}

.navbar-login
{  padding-left:50px;
    padding: 10px;
    padding-bottom: 0px;
}
.dropprofile{ padding:0px;}
.boxcolorfooter{ padding:5px; color:#fff;overflow:hidden;}
.imagesrightnav ul li {
    text-decoration: none;
    display: inline;
    padding: 0px 0px 2px 2px!important;
    font-weight: bold;
    font-size: 11px; 
}

.navbar-login-session
{
    padding: 10px;
    padding-bottom: 0px;
    padding-top: 0px;
}

.icon-size
{
}
.clear {
        clear: both;
    }
.headright ul {
        margin: 0px;
        padding: 0px;
    }
.righttab{ margin-top:18px!important; color:#000; width:400px; padding:10px!important;left:-350px!important;}

.righttab:before {
    border: inset 9px;
    content: "";
    display: block;
    height: 0;
    width: 0;
    border-color: transparent transparent #FFFFFF transparent;
    border-bottom-style: solid;
    position: absolute;
    top: -18px;
    left: 360px;
    z-index: 89;
    font-size: 15px;
}
.mainname{ color:#000; font-size:15px; font-weight:normal;}
.mainnamesub{ color:#ABA6A6; font-size:12px;font-weight:normal;}
.boxcolor{ background:#efeeee; padding:10px; text-align:center;width: 74px;}
.boxcolor:hover{ background:#2980b9; padding:10px; text-align:center; color:#fff; cursor:pointer}
.boxcolor01{ background:#C3C3C3; padding:5px; text-align:center; color:#000!important; width:92px;overflow:hidden;}
divider02{}
.mainnamesub a{color:#2980b9!important;}
.mainnamesub a:hover{color:#000!important;}
.boxcolor01 a{  color:#fff!important;}
.boxcolor01:hover{ background:#000;}
@media screen and (max-width: 568px) {
.righttab {
    margin-top: 18px!important;
    color: #000;
    width: 259px;
    padding: 0px!important;
    left: -76px!important;
}      	
.mainnamesub {
font-size: 12px;}
.righttab:before {top: -18px;left: 85px;}
}
.boxcolor {
    background: #efeeee none repeat scroll 0 0;
    color: black;
    padding: 10px;
    text-align: center;
    width: 74px;
}
.sginoutbt{    float: right;}

.navbar-collapse {
    padding-right: 15px;
    padding-left: 15px;
    overflow-x: visible;
    -webkit-overflow-scrolling: touch;
    border:none!important;
    -webkit-box-shadow: none!important;
    box-shadow:none!important;
}
.navbar-inverse {
    background-color: #333!important;
    border-color: transparent!important;
}

.custom-right-menu li {
    border-right: 1px solid #444;
    padding: 0 10px;
    margin-top: 4px;
}

.dropdown,
.dropdown .btn {}.show-menu,
li a {
    text-decoration: none
}
.cat-head h5,
.jobs-lists h4,
.top-emp h4 {
    text-transform: uppercase;
    font-weight: 700
}
.cat-head h5,
.emp-head h3,
.footer,
.job-vac,
.jobs-lists h4,
.menu-ic,
.top-emp h4 {
    text-transform: uppercase
}
.main-ic:hover,
.menu-ic:hover {
    -webkit-text-fill-color: transparent
}
body {
    font-family: Roboto, sans-serif!important
}
.navbar {
    border-radius: 0!important;
    z-index: 1
}
.navbar-inverse {
    background-color: #333!important;
    border-color: transparent!important
}
.tj-banner {
    position: absolute;
    z-index: -1;
    top: 0
}
#cf,
.nav>li {
    position: relative
}

.nav>li,
.show-menu {
    text-align: center;
    float: right
}

.footer {
    background-color: #1d2738;
    padding-bottom: 20px;
}
.fot-head h4 {
    color: #3498db;
    font-size: 14px;
    padding: 15px 0
}
.fot-links ul li:before {
    font-family: FontAwesome;
    content: "\f105";
    color: #3498db;
    position: absolute
}
.custom_select_box_holder .form-group:after,
.select-box:after {
    content: "\f0d7";
    position: absolute;
    right: 15px
}
.fot-links ul li a {
    padding-left: 20px;
    padding-bottom: 5px;
    color: #4f6588;
    font-size: 13px
}
.blu-line {
    background-color: #22334e;
    height: 1px;
    margin: 20px 0
}
.socil-ic h5 {
    display: inline;
    float: left;
    color: #4f6588;
    padding-right: 20px
}
.socil-ioc li {
    float: left;
    padding: 0 3px;
    display: inline-block!important
}
.copy-link h5 {
    color: #4f6588;
    float: right;
    text-align: right;
    font-size: 13px
}
.main-ic,
.menu-ic {
    text-align: center
}
select.input-lg {
    line-height: 25px!important
}
.btn-group-lg>.btn,
.btn-lg {
    padding: 2px 16px!important;
    border-radius: 3px!important
}
.drop-loc {
    padding-left: 10px
}
.loc {
    border-radius: 3px 0 0 3px
}
.drop-down {
    border-bottom-left-radius: 5px!important;
    border-bottom-right-radius: 5px!important
}
.clear {
    padding: 10px 30px!important
}
.carousel-indicators .active {
    width: 12px;
    height: 12px;
    margin: 0;
    background-color: #222d40
}
.pad-bot {
    padding-bottom: 50px
}
.container .home_main_search_form_holder {
    margin: 30px 0;
    height: auto
}
.home_main_search_form_top {
    background-color: #bec3c7;
    padding: 15px;
    overflow: auto;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    border-top: 1px solid #fff;
    border-right: 1px solid #fff;
    border-left: 1px solid #fff;
    border-bottom: 1px solid #9da2a6
}
.input-group-addon {
    background-color: #3498db!important;
    color: #fff!important;
    cursor: pointer;
    border-top: 1px solid #7f878d!important;
    border-right: 1px solid #7f878d!important;
    border-bottom: 1px solid #9da2a6!important
}
.input-group-addon:hover {
    background-color: #1c77b4!important
}
.home_main_search_form_top .select-box {
    padding: 0!important
}
.select-box select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    border-top-right-radius: 0;
    border-bottom-right-radius: 0
}
.select-box:after {
    font-family: FontAwesome;
    top: 10px;
    font-size: 14px
}
.home_main_search_form_top .input-box .form-group {
    margin-bottom: 0
}
.home_main_search_form_top .input-box {
    padding-left: 0!important
}
.home_main_search_form_top .input-box .form-control {
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-left: 0
}
.btn-custom-green {
    border-color: #008D3D!important;
    background-color: #00B850;
    color: #FFF;
    cursor: pointer;
    font-size: 14px;
    padding: 8px 32px!important
}
.btn-custom-green:focus,
.btn-custom-green:hover {
    background-color: #01a449;
    color: #fff
}
.home_main_search_form_top input {
    font-size: 14px!important;
    color: #99999F!important;
    font-family: arial!important;
    font-weight: 400!important
}
.home_main_search_form_top select {
    font-size: 14px;
    color: #99999F!important;
    line-height: 14px
}
@media (min-width: 991px) and (max-width: 1199px) {
    .input-box {
        width: 55%
    }
}
@media (max-width: 991px) {
    .home_main_search_form_top .input-box {
        padding: 0;
        margin-bottom: 10px
    }
    .home_main_search_form_top .select-box {
        margin-bottom: 10px
    }
    .home_main_search_form_top .input-box .form-control {
        border-top-left-radius: 4px;
        border-bottom-left-radius: 4px;
        border-left: 4px!important;
        border-left: 1px!important
    }
    .select-box select {
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px
    }
    .btn-custom-green {
        float: right
    }
}
.home_main_search_form_bottom {
    overflow: auto;
    border: 1px solid #FFF;
    padding: 15px 10px 10px;
    display: none;
    background: #c0c5c9;
    background: -moz-linear-gradient(top, #c0c5c9 0, #c0c5c9 0, #ecf0f1 21%, #ecf0f1 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0, #c0c5c9), color-stop(0, #c0c5c9), color-stop(21%, #ecf0f1), color-stop(100%, #ecf0f1));
    background: -webkit-linear-gradient(top, #c0c5c9 0, #c0c5c9 0, #ecf0f1 21%, #ecf0f1 100%);
    background: -o-linear-gradient(top, #c0c5c9 0, #c0c5c9 0, #ecf0f1 21%, #ecf0f1 100%);
    background: -ms-linear-gradient(top, #c0c5c9 0, #c0c5c9 0, #ecf0f1 21%, #ecf0f1 100%);
    background: linear-gradient(to bottom, #c0c5c9 0, #c0c5c9 0, #ecf0f1 21%, #ecf0f1 100%);
    filter: progid: DXImageTransform.Microsoft.gradient(startColorstr='#c0c5c9', endColorstr='#ecf0f1', GradientType=0)
}
.home_main_search_form_bottom .form-control {
    border-radius: 0;
    border-color: #ddd
}
.home_main_search_form_bottom .btn-light-blue {
    background-color: #aedfff;
    color: #333;
    margin-top: 18px
}
.home_main_search_form_bottom .btn-light-blue:hover {
    background-color: #92c9ed;
    color: #333
}
.home_main_search_form_bottom .exp_block,
.home_main_search_form_bottom .location_block,
.home_main_search_form_bottom .sal_block {
    padding-left: 0!important
}
.home_main_search_form_bottom .exp_block select,
.home_main_search_form_bottom .sal_block select {
    width: 49%
}
@media (max-width: 991px) {
    .home_main_search_form_bottom .exp_block,
    .home_main_search_form_bottom .location_block,
    .home_main_search_form_bottom .sal_block {
        padding-right: 0!important
    }
    .home_main_search_form_bottom .btn-light-blue {
        margin-top: 0;
        float: right
    }
    .custom_select_box_holder {
        padding-right: 0
    }
}
@media (max-width: 767px) {
    .home_main_search_form_bottom .exp_block select,
    .home_main_search_form_bottom .sal_block select {
        width: 100%
    }
}
.custom_select_box_holder {
    padding-left: 0
}
.custom_select_box_holder select {
    width: 100%
}
.custom_select_box_holder .form-group select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none
}
.custom_select_box_holder .form-group:after {
    font-family: FontAwesome;
    top: 37px;
    font-size: 14px
}
.menu-ic {
    padding: 25px 0
}
.menu-ic h4 {
    color: #fff
}
.menu-ic:hover {
    -webkit-background-clip: text
}
.main-ic:hover,
.menu-ic:hover {
    background: -webkit-linear-gradient(#2e8fee, #00d457)
}
.small-txt {
    font-size: 12px
}
.main-ic {
    color: #fff;
    font-size: 30px
}
#s2id_autogen3,
#s2id_autogen5,
#s2id_skill_key .select2-choices,
#s2id_skill_keyword .select2-choices,
.select2-default,
.select2-results {
    color: #555!important
}
.main-ic:hover {
    font-weight: 500;
    -webkit-background-clip: text
}
.top-emp {
    padding-bottom: 20px
}
.employers ul li {
    display: inherit;
    float: left;
    padding: 0 4px
}
.comman {
    float: right;
    line-height: 0;
    margin-right: -3px;
    margin-top: -3%
}
#s2id_skill_keyword .select2-choices {
    margin: -1.5% -2.45% 0 -2.19%;
    font-size: 13px
}
#s2id_skill_key .select2-choices {
    margin: -1.56% -2.5% 0 -2.05%;
    font-size: 13px
}
#s2id_job_location .select2-choices {
    margin: -2.5% -3.56% 0
}
#s2id_company_location .select2-choices {
    margin: -2.09% -2.69% 0;
    color: #555!important
}
.select2-container-multi .select2-choices li {
    float: left;
    list-style: none;
    padding-top: 2%
}
#s2id_autogen2,
#s2id_autogen4 {
    position: relative;
    max-width: 500px!important
}
.select2-container-multi .select2-choices {
    border: 0
}
#s2id_company_location .comman,
#s2id_job_location .comman {
    margin-top: -3%
}
.btn-custom-green:hover {
    color: #fff!important
}
.custom-right-menu li {
    border-right: 1px solid #444;
    padding: 0 10px;
    margin-top: 4px
}



















</style>

<!--********************************************************************************************** -->
<!--**************************************Header Starts Here************************************** -->
<!--********************************************************************************************** -->
<div style="background:#333;">
<div class="container-fluid navbar-pad-bot">
		<div class="navbar-header">

<a href="<?php echo Url::to('http://takeajob.com'); ?>">
    			<?php $path = Yii::$app->homeUrl . 'img/'; ?>
    			<?= Html::img($path.'tj-logo.png', ['alt'=>'Take a Job']);?></a>
		</div>
		<div class="right-menu">
		<label for="show-menu" class="show-menu">
		</label>
		<ul class="nav navbar-nav navbar-right custom-right-menu" id="menu">
			<li>
				<a href="#"><i class="fa fa-bars"></i></a>
			</li>
			 <?php if(Yii::$app->user->isGuest)
                    {
                       // $user = Yii::$app->user->identity;
					?><li>
				<a href="http://jobseeker.takeajob.com/register"><i class="fa fa-file-o"></i>&nbsp;Post Your CV</a>
			</li>
		<li>
				<a href="http://employer.takeajob.com"><i class="fa fa-briefcase"></i>&nbsp;Employer Login</a>
			</li>
			<li>
				<a href="http://jobseeker.takeajob.com/login"><i class="fa fa-user"></i>&nbsp;Jobseeker Login</a>
			</li>
					<?php }?>
			
		</ul>
		</div>
	
        <div class="container">
			<?php if(!Yii::$app->user->isGuest){ ?>
            <nav> <a id="resp-menu" class="responsive-menu" href="#"><i class="fa fa-reorder"></i> Menu</a>
                <ul class="menu">
                    <li><?php echo Html::a('Main Menu', ['/dashboard/default/index']);?>
                    </li>
                    <li><a href="#">Jobs & Responses</a>
                        <ul class="sub-menu">
                            <li><?php echo Html::a('Post a Hot Vacancy', ['/job/default/postahotvacancy']);?></li>
                            <li><?php echo Html::a('Manage Jobs & Responses', ['/job/default/managejobs']);?></li>
                            <li><?php echo Html::a('My Folders', ['/folders/personal/managefolders']);?></li>
                            <li><?php echo Html::a('Questionnaires',  ['/question/questionnaire/managequestion']);  ?> </li>
                            <li><?php echo Html::a('Email Templates',  ['/template/template/index']);  ?></li>
                        </ul>
                    </li>
                    <li><a href="#"> Resdex</a>
                        <ul class="sub-menu">
                            <li><?php echo Html::a('Advanced Search', ['/search/search/advanced_search']);?> </li>
							<li><?php echo Html::a('Easy Search', ['/search/search/easy_search']);?> </li>
							<li><?php echo Html::a('Special Search', ['/search/search/special_search']);?> </li>
							<li><?php echo Html::a('Role Search', ['/search/search/role_search']);?> </li>
                        </ul>
                    </li>

                    <li><a href="#">Reports</a>
                        <ul class="sub-menu">
                            <li><a href="#">Usage</a></li>
                            <li><a href="#">Referral Reports</a></li>
                        </ul>
                    </li>

                    <li><a href="#">Administration</a>

                        <ul class="sub-menu">
                            <li> <?php echo Html::a('Change Password',
                        ['/site/changepassword'],
                        [ 'data-method'=>'post']);  ?>
                            </li>
							
                            <li><a href="#">Usage Guidelines</a>
                            </li>
							<li>
                         <?php echo Html::a('Logout', ['/site/logout'], [ 'data-method'=>'post']);  ?>
                        </li>
                        </ul>

                    </li>
                    <!--<li><a href="#">NaukriRecruiter</a>
                        <ul class="sub-menu">
                            <li><a href="#">Home</a>
                            </li>
                            <li><a href="#">Edit Profile</a>
                            </li>
                            <li><a href="#">People Following Me</a>
                            </li>
                            <li><a href="#">Who Viewed My Profile</a>
                            </li>
                            <li><a href="#">Inbox Applies</a>
                            </li>
                            <li><a href="#">Post Quick Job</a>
                            </li>
                        </ul>

                    </li>
                    <li><a href="#">Referral </a>

                        <ul class="sub-menu">
                            <li><a href="#">Create Referral</a>
                            </li>
                            <li><a href="#">Track Referral</a>
                            </li>
                            <li><a href="#">Manage Users</a>
                            </li>
                            <li><a href="#">Referral Settings</a>
                            </li>
                            <li><a href="#">Manage Referral Jobs</a>
                            </li>
                            <li><a href="#">Referral Reports</a>
                            </li>

                        </ul>
                    </li>-->
                </ul>
            </nav>
			<?php } ?>
		</div>

		
		
		
		
		
		
		
		
		
	</div>
</div>







	
	
	
	
	
	
	
	
	

    <?= $content ?>
	
	
	<section class="footer">
	<div class="container">
		<div class="row">
			<div class="col-md-3">
				<div class="fot-head">
					<h4>Information</h4>
				</div>
				<div class="fot-links">
				<ul>
					<li><a href="/aboutus">About Us</a></li>
					<li><a href="/terms-conditions">Terms &amp; Conditions</a></li>
					<li><a href="/privacy-policy">Privacy Policy</a></li>
					<li><a href="/contact-us">Contact Us</a></li>
					<li><a href="/faq">FAQs</a></li>
				</ul>
				</div>
			</div>
			<div class="col-md-3">
				<div class="fot-head">
					<h4>Jobseekers</h4>
				</div>
				<div class="fot-links">
					<ul>
						<li><a href="/jobseeker">Job Seeker</a></li>
						<li><a href="http://jobseeker.takeajob.com/register">Register Now</a></li>
						<li><a href="http://jobseeker.takeajob.com/job-search">Search Jobs</a></li>
						<li><a href="#">Map Search Jobs</a></li>
						<li><a href="http://jobseeker.takeajob.com/login">Login</a></li>
					</ul>
				</div>
			</div>
			<div class="col-md-3">
				<div class="fot-head">
					<h4>Browse Jobs</h4>
				</div>
				<div class="fot-links">
					<ul>
						<li><a href="http://jobseeker.takeajob.com/search/jobs-by-company">Jobs by Company</a></li>
						<li><a href="http://jobseeker.takeajob.com/search/jobs-by-category">Jobs by Category</a></li>
						<li><a href="http://jobseeker.takeajob.com/search/jobs-by-designation">Jobs by Designation</a></li>
						<li><a href="http://jobseeker.takeajob.com/search/jobs-by-location">Jobs by Location</a></li>
						<li><a href="http://jobseeker.takeajob.com/search/jobs-by-skill">Jobs by Skill</a></li>
					</ul>
				</div>
			</div>
			<div class="col-md-3">
				<div class="fot-head">
					<h4>Employers</h4>
				</div>
				<div class="fot-links">
					<ul>
						<li><a href="employer">Employer</a></li>
					</ul>
				</div>
			</div>
			<div class="col-md-12">
				<div class="blu-line"></div>
				<div class="col-md-6">
					<div class="socil-ic">
						<h5>Follow us on</h5>
					
						<ul class="socil-ioc">
							<li><a href="https://www.facebook.com/pages/takeajobcom/981599551907535"><?= Html::img($path.'f.jpg', ['alt'=>'Take a Job']);?></a></a></li>
							<li><a href="https://twitter.com/takeajob"><?= Html::img($path.'t.jpg', ['alt'=>'Take a Job']);?></a></a></li>
							<li><a href="javascript:void(0)"><?= Html::img($path.'in.jpg', ['alt'=>'Take a Job']);?></a></a></li>
						</ul>
					</div>
				</div>
				<div class="col-md-6 copy-link">
					<h5>All rights reserved @ 2015 take a job</h5>
				</div>
			</div>
		</div>
	</div>
</section>
	
	
	
	
	
	
	
	
	
	

     <?php /*$this->registerJs('
            $("#nav-mobile").html($("#nav-main").html());
            $("#nav-trigger span").click(function() {
                if ($("nav#nav-mobile ul").hasClass("expanded")) {
                    $("nav#nav-mobile ul.expanded").removeClass("expanded").slideUp(250);
                    $(this).removeClass("open");
                } else {
                    $("nav#nav-mobile ul").addClass("expanded").slideDown(250);
                    $(this).addClass("open");
                }
            });
			$(".panel-heading span.clickable").on("click", function(e) {
                if ($(this).hasClass(\'panel-collapsed\')) {
                    // expand the panel
                    $(this).parents(\'.panel\').find(\'.panel-body\').slideDown();
                    $(this).removeClass(\'panel-collapsed\');
                    $(this).find(\'i\').removeClass(\'glyphicon-chevron-down\').addClass(\'glyphicon-chevron-up\');
                } else {
                    // collapse the panel
                    $(this).parents(\'.panel\').find(\'.panel-body\').slideUp();
                    $(this).addClass(\'panel-collapsed\');
                    $(this).find(\'i\').removeClass(\'glyphicon-chevron-up\').addClass(\'glyphicon-chevron-down\');
                }
            });

            $(\'.taj-panel\').click(function() {
                setTimeout(function() {
                    $(\'.ras\').addClass("open");
                }, 10);

            });
			/*$(\'input\').iCheck({
                checkboxClass: \'icheckbox_square\',
                radioClass: \'iradio_square\',
                increaseArea: \'20%\' // optional
            });
			 $(\'input\').iCheck({
                checkboxClass: \'icheckbox_square-green\',
                radioClass: \'iradio_square-green\',
                increaseArea: \'20%\' // optional
            });
			 $(\'[data-toggle="tooltip"]\').tooltip();   

      ');*/
    ?>
	<script>
	$("#nav-mobile").html($("#nav-main").html());
            $("#nav-trigger span").click(function() {
                if ($("nav#nav-mobile ul").hasClass("expanded")) {
                    $("nav#nav-mobile ul.expanded").removeClass("expanded").slideUp(250);
                    $(this).removeClass("open");
                } else {
                    $("nav#nav-mobile ul").addClass("expanded").slideDown(250);
                    $(this).addClass("open");
                }
            });
			$(".panel-heading span.clickable").on("click", function(e) {
                if ($(this).hasClass(\'panel-collapsed\')) {
                    // expand the panel
                    $(this).parents(\'.panel\').find(\'.panel-body\').slideDown();
                    $(this).removeClass(\'panel-collapsed\');
                    $(this).find(\'i\').removeClass(\'glyphicon-chevron-down\').addClass(\'glyphicon-chevron-up\');
                } else {
                    // collapse the panel
                    $(this).parents(\'.panel\').find(\'.panel-body\').slideUp();
                    $(this).addClass(\'panel-collapsed\');
                    $(this).find(\'i\').removeClass(\'glyphicon-chevron-up\').addClass(\'glyphicon-chevron-down\');
                }
            });

            $(\'.taj-panel\').click(function() {
                setTimeout(function() {
                    $(\'.ras\').addClass("open");
                }, 10);

            });
			/*$(\'input\').iCheck({
                checkboxClass: \'icheckbox_square\',
                radioClass: \'iradio_square\',
                increaseArea: \'20%\' // optional
            });
			 $(\'input\').iCheck({
                checkboxClass: \'icheckbox_square-green\',
                radioClass: \'iradio_square-green\',
                increaseArea: \'20%\' // optional
            });*/
			 $(\'[data-toggle="tooltip"]\').tooltip(); 
	</script>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
