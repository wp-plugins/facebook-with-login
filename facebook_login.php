<?php
session_start();
ini_set('allow_url_fopen', 'On');



/*
Plugin Name: Facebook with login
Plugin URI: http://themecrest.com/
Description: Get Access Of the site with login in facebook account..
Author: Themecrest
Version: 1.0
Author URI:http://themecrest.com/
*/


add_action("widgets_init", array('My_Login', 'register'));
register_activation_hook( __FILE__, array('My_Login', 'activate'));
register_deactivation_hook( __FILE__, array('My_Login', 'deactivate'));
class My_Login {
  function activate(){
    $data = array( 'option1' => 1 ,'option2' => 1,'option3' => 1 ,'option4' => 1,'option5' => 1 ,'option6' => 1);
    if ( ! get_option('My_Login')){
      add_option('My_Login' , $data);
    } else {
      update_option('My_Login' , $data);
    }
  }
  function deactivate(){
    delete_option('My_Login');
  }
  function control(){
    $data = get_option('My_Login');
	//print_r($data);
  ?>
  <div id="select_custom">Default Action
  <select name="default">
  <option value="1" <?php if($data['option1']==1) { echo "selected=selected"; }?>>Login </option>
  <option value="2" <?php if($data['option1']==2){ echo "selected=selected"; }?>>Register</option>
  <option value="3" <?php if($data['option1']==3) { echo "selected=selected";}?>>Lost Password</option>
  </select></div>
  <div id="custom_widget">
<div class="custom">  <input type="checkbox" name="showtitle" value="0" <?php if($data['option2']==1) { echo "checked";} ?> />Show Title</div>
<div class="custom">  <input type="checkbox" name="login" value="0" <?php if($data['option3']==1) { echo "checked";} ?> />Show Login Link</div>
<div class="custom">  <input type="checkbox" name="register" value="0" <?php if($data['option4']==1) { echo "checked";} ?> />Show Register Link</div>
<div class="custom">  <input type="checkbox" name="lost" value="0" <?php if($data['option5']==1) { echo "checked";} ?> />Show Lost Password Link</div>
</div>

  <?php
   if (isset($_POST['default'])){
    $data['option1'] = attribute_escape($_POST['default']);
	if(isset($_POST['showtitle'])){	$data['option2'] = 1;	}	else	{		$data['option2'] = 0;	}
	if(isset($_POST['login']))	{	$data['option3'] = 1;	}	else	{		$data['option3'] = 0;	}
	if(isset($_POST['register'])){	$data['option4'] = 1;	}	else	{		$data['option4'] = 0;	}
	if(isset($_POST['lost']))	{	$data['option5'] = 1;	}	else	{		$data['option5'] = 0;	}
  
    update_option('My_Login', $data);
  }
  }
  function widget($args){
  ?> <link rel="stylesheet" href="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/stylesheets/custom.css'; ?>" type="text/css" media="screen" />

  <?php
  	$data = get_option('My_Login');
	
    echo $args['before_widget'];
    echo $args['before_title'] ;
	if($data['option2']==1)
	{
		if($data['option1']==1)	{	echo "Log In";	}	if($data['option1']==2)	{ echo "Register";	}if($data['option1']==3)	{ echo "Lost Password?";	}
	}
	else
	{
	
	}
	echo $args['after_title'];
ini_set('allow_url_fopen', 'On');
 if (is_user_logged_in()) 
{
		global $user_identity;
		 global $current_user;
			  get_currentuserinfo();?>
		<div class="error_msg"><?php echo $_SESSION['success'];$_SESSION['success']='';?></div>
		<div class="welcome_text">Welcome <strong><?php echo $user_identity; ?></strong>.</div>
		<?php if($_SESSION['loginstatus'] != "")
		{
		$i = "select * from fb_custom where id = 3";
				$i1 = mysql_query($i);
				$final_url = mysql_fetch_row($i1);
		?>
        <?php

    include_once "fbmain.php";
    $config['baseurl']  =   get_bloginfo('siteurl')."/wp-content/plugins/facebookwithlogin/try.php";


 $logoutUrl  = $facebook->getLogoutUrl();

    //if user is logged in and session is valid.
    if ($fbme){
        //Retriving movies those are user like using graph api
        try{
            $movies = $facebook->api('/me/movies');
        }
        catch(Exception $o){
            d($o);
        }
 
        //Calling users.getinfo legacy api call example
        try{
            $param  =   array(
                'method'  => 'users.getinfo',
                'uids'    => $fbme['id'],
                'fields'  => 'name,current_location,profile_url',
                'callback'=> ''
            );
            $userInfo   =   $facebook->api($param);
        }
        catch(Exception $o){
            d($o);
        }
 
        //update user's status using graph api
        if (isset($_POST['tt'])){
            try {
                $statusUpdate = $facebook->api('/me/feed', 'post', array('message'=> $_POST['tt'], 'cb' => ''));
            } catch (FacebookApiException $e) {
                d($e);
            }
        }
 
        //fql query example using legacy method call and passing parameter
        try{
            //get user id
            $uid    = $facebook->getUser();
            //or you can use $uid = $fbme['id'];
 
            $fql    =   "select name, hometown_location, sex, pic_square from user where uid=" . $uid;
            $param  =   array(
                'method'    => 'fql.query',
                'query'     => $fql,
                'callback'  => ''
            );
            $fqlResult   =   $facebook->api($param);
        }
        catch(Exception $o){
            d($o);
        }
    }
?>

    <div id="fb-root"></div>
        <script type="text/javascript">
            window.fbAsyncInit = function() {
                FB.init({appId: '<?php echo $fbconfig['appid' ]?>', status: true, cookie: true, xfbml: true});
 
                /* All the events registered */
                FB.Event.subscribe('auth.login', function(response) {
                    // do something with response
                    login();
                });
                FB.Event.subscribe('auth.logout', function(response) {
                    // do something with response
					//alert('sv');
                    logout();
                });
            };
            (function() {
                var e = document.createElement('script');
                e.type = 'text/javascript';
                e.src = document.location.protocol +
                    '//connect.facebook.net/en_US/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }());
 
            function login(){
                document.location.href = "<?php echo $config['baseurl']?>";
            }
            function logout(){

			
                document.location.href = "<?php echo $config['baseurl']?>";
				
            }
			function demo()
			{
			document.location.href = "<?php echo $logoutUrl; ?>";
			 FB.init({appId: '<?php echo $fbconfig['appid' ]?>', status: false, cookie: false, xfbml: false});
			 FB.Event.subscribe('auth.logout', function(response) {
                    // do something with response
					//alert('sv');
                    logout();
                });
			}
</script>
<style type="text/css">
    .box{
        margin: 5px;
        border: 1px solid #60729b;
        padding: 5px;
        width: 500px;
        height: 200px;
        overflow:auto;
        background-color: #e6ebf8;
    }
</style>
 
		<div class="logout"><a onClick="FB.logout(); return false;" href="<?php echo wp_logout_url( $final_url[3] ); ?>" title="Logout">Logout</a></div>
		<?php
		}
		else
		{
		$i = "select * from fb_custom where id = 3";
				$i1 = mysql_query($i);
				$final_url = mysql_fetch_row($i1);
		?>
		<div class="logout"><a  href="<?php echo wp_logout_url( $final_url[3] ); ?>" title="Logout">Logout</a></div>
		<?php
		}
		?>
        <div class="custom_link">
        <a href='<?php echo get_bloginfo('siteurl')."/?page_id=".wt_get_ID_by_page_name('mylogin')."#1'";?>'>Change Password</a>
        <a href='<?php echo get_bloginfo('siteurl')."/?page_id=".wt_get_ID_by_page_name('mylogin')."#2'";?>'>Edit Profile</a>
        <a href='<?php echo get_bloginfo('siteurl')."/?page_id=".wt_get_ID_by_page_name('mylogin')."#3'";?>'>Use Facebook</a>
        
        </div>
		<?php
	
}
else
{
   include('check.php');
}	 // print_r($data);
    echo $args['after_widget'];
	
  }
  function register(){
    register_sidebar_widget('My_Login', array('My_Login', 'widget'));
    register_widget_control('My_Login', array('My_Login', 'control'));
  }
}

//url redirection part


//end of url redirection
add_shortcode("login", "demolistposts_handler1");

function demolistposts_handler1() {
  //run function that actually does the work of the plugin
  $demolph_output = demolistposts_function1();
  //send back text to replace shortcode in post
  return $demolph_output;
}

function demolistposts_function1() {

global $user_identity;
?>

 <link rel="stylesheet" href="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/stylesheets/reset.css'; ?>" type="text/css" media="screen" />
  <link rel="stylesheet" href="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/stylesheets/custom.css'; ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/stylesheets/coda-slider-2.0.css'; ?>" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/javascripts/jquery-1.3.2.min.js';?>"></script>
<script type="text/javascript" src="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/javascripts/jquery.easing.1.3.js';?>"></script>
<script type="text/javascript" src="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/javascripts/jquery.coda-slider-2.0.js';?>"></script>
		 <script type="text/javascript">
			$().ready(function() {
				$('#coda-slider-1').codaSlider();
			});
		 </script>
<?php
		 if (is_user_logged_in()) { 
		 global $current_user;
      get_currentuserinfo();?>
<div class="error_msg"><?php echo $_SESSION['success'];$_SESSION['success']='';?></div>
<div class="welcome_text">Welcome <strong><?php echo $user_identity ?></strong>.</div>
<?php if($_SESSION['loginstatus'] != "")
{
 $i = "select * from fb_custom where id = 3";
				$i1 = mysql_query($i);
				$final_url = mysql_fetch_row($i1);
?>
<div class="logout"><a onClick="FB.logout(); return false;" href="<?php  echo wp_logout_url( $final_url[3] ); ?>" title="Logout">Logout</a></div>
<?php
}
else
{
$i = "select * from fb_custom where id = 3";
				$i1 = mysql_query($i);
				$final_url = mysql_fetch_row($i1);
				$$redirect = $final_url[3];
?>
<div class="logout"><a  href="<?php  echo wp_logout_url( $final_url[3] ); ?>" title="Logout">Logout</a></div>
<?php
}
?>
<script language='JavaScript' type='text/JavaScript'>
<!--
function validate() {
	if(document.form1.new.value=='')
		{
		alert('Please Enter Password Field');
		return false;
		}
		if(document.form1.cnew.value=='')
		{
		alert('Please Enter Confirm Password');
		return false;
		}
		if(document.form1.cnew.value != document.form1.new.value)
		{
		alert('Password and Confirm password do not match');
		return false;
		}
	
	return true;
}
function editprofile() {
	if(document.form2.fname.value=='')
		{
		alert('Please Enter First Name');
		return false;
		}
		if(document.form2.lname.value=='')
		{
		alert('Please Enter Last Name');
		return false;
		}
		if(document.form1.email.value =='')
		{
		alert('Please Enter Email');
		return false;
		}
		if(document.form2.url.value=='')
		{
		alert('Please Enter Site Url');
		return false;
		}
		if(document.form1.binfo.value =='')
		{
		alert('Please Enter Biographical info');
		return false;
		}
	
	return true;
}
//-->
</script>
 <div class="coda-slider-wrapper">
	<div class="coda-slider preload" id="coda-slider-1">
		<div class="panel">
			<div class="panel-wrapper">
				<h2 class="title">Change Password</h2>
                <?php 
				$log_with_fb=$_SESSION['log_with_fb'];
				if(isset($log_with_fb))
				{
				?>
                <div id="change_pass">
                <h2>New User?</h2>

				<form name='form1' action="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/changepassword.php';?>" method="post" onSubmit='return validate();'>
              
                <table>
                <tr><td>User Name:</td><td><?php echo $current_user->user_login; ?></td></tr>
                <tr><td>New Password:</td><td><input type="password" name="new" id="new"></td></tr>
                <tr><td>Confirm New Password</td><td><input type="password" name="cnew"></td></tr>
                <input type="hidden" name="id" value="<?php echo $current_user->ID; ?>" >
                <tr><td></td><td><input type="submit" name="submit" value="change password" ></td></tr>
                </table>
                </form>
                </div><!--change pass-->
                <div id="exist_mem">
                <h2>Already Registered?</h2>
                <form name="exist_login" id="exist_login" action="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/exist_mem.php';?>" method="post">
           
                 <label for="log">Username</label> <input type="text" name="log" id="user_login" class="input" value="" size="20" tabindex="10" /></p>
                 <label for="pwd">Password</label> <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" tabindex="20" /></p>
                 <input type="hidden" name="id" value="<?php echo $current_user->ID; ?>" >
                 <input type="submit" name="wp-submit" id="wp-submit" value="LOG IN" />  
                    
                 </form> 
                
                </div><!--exist_mems-->
                
                <?php
				}
			    else
				{?>
				     <form name='form1' action="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/changepassword.php';?>" method="post" onSubmit='return validate();'>
                <table>
                <tr><td>User Name:</td><td><?php echo $current_user->user_login; ?></td></tr>
                <tr><td>New Password:</td><td><input type="password" name="new" id="new"></td></tr>
                <tr><td>Confirm New Password</td><td><input type="password" name="cnew"></td></tr>
                <input type="hidden" name="id" value="<?php echo $current_user->ID; ?>" >
                <tr><td></td><td><input type="submit" name="submit" value="change password" ></td></tr>
                </table>
                </form>
				<?php }
				
				?>
				
			</div>
		</div> <!--exit section1-->
		<div class="panel">
			<div class="panel-wrapper">
				<h2 class="title">Edit Profile</h2>
                
                        <form name="form2" onSubmit='return editprofile();' action="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/updateprofile.php';?>" method="post">
              
                <table>
                <?php echo $_SESSION['profile']; $_SESSION['profile']="";?>
                <tr><td>User Name:</td><td style="float: left; width: 160px;"><?php echo $current_user->user_login; ?></td><td>Site Url</td><td><input type="text" name="url" value="<?php echo $current_user->user_url; ?>"></td></tr>
                <tr><td>First Name:</td><td><input type="text" name="fname" value="<?php echo $current_user->first_name; ?>"></td><td>Biographical Info</td><td><input type="text" name="binfo" value="<?php echo $current_user->description; ?>"></td></tr>
                <tr><td>Last Name:</td><td><input type="text" name="lname" value="<?php echo $current_user->last_name; ?>"></td></tr>
                <tr><td>E-Mail:</td><td><input type="text" name="email" value="<?php echo $current_user->user_email; ?>"></td></tr>
                <input type="hidden" name="id" value="<?php echo $current_user->ID; ?>" >
                <tr><td></td><td><input type="submit" name="submit" value="Update Profile"></td></tr>
                </table>
                </form>
			</div>
		</div>
       
<!-- If User Is NOT Logged In Display This -->
<?php

    include_once "fbmain.php";
    $config['baseurl']  =   get_bloginfo('siteurl')."/wp-content/plugins/facebookwithlogin/try.php";


 $logoutUrl  = $facebook->getLogoutUrl();

    //if user is logged in and session is valid.
    if ($fbme){
        //Retriving movies those are user like using graph api
        try{
            $movies = $facebook->api('/me/movies');
        }
        catch(Exception $o){
            d($o);
        }
 
        //Calling users.getinfo legacy api call example
        try{
            $param  =   array(
                'method'  => 'users.getinfo',
                'uids'    => $fbme['id'],
                'fields'  => 'name,current_location,profile_url',
                'callback'=> ''
            );
            $userInfo   =   $facebook->api($param);
        }
        catch(Exception $o){
            d($o);
        }
 
        //update user's status using graph api
        if (isset($_POST['tt'])){
            try {
                $statusUpdate = $facebook->api('/me/feed', 'post', array('message'=> $_POST['tt'], 'cb' => ''));
            } catch (FacebookApiException $e) {
                d($e);
            }
        }
 
        //fql query example using legacy method call and passing parameter
        try{
            //get user id
            $uid    = $facebook->getUser();
            //or you can use $uid = $fbme['id'];
 
            $fql    =   "select name, hometown_location, sex, pic_square from user where uid=" . $uid;
            $param  =   array(
                'method'    => 'fql.query',
                'query'     => $fql,
                'callback'  => ''
            );
            $fqlResult   =   $facebook->api($param);
        }
        catch(Exception $o){
            d($o);
        }
    }
?>

    <div id="fb-root"></div>
        <script type="text/javascript">
            window.fbAsyncInit = function() {
                FB.init({appId: '<?php echo $fbconfig['appid' ]?>', status: true, cookie: true, xfbml: true});
 
                /* All the events registered */
                FB.Event.subscribe('auth.login', function(response) {
                    // do something with response
                    login();
                });
                FB.Event.subscribe('auth.logout', function(response) {
                    // do something with response
					//alert('sv');
                    logout();
                });
            };
            (function() {
                var e = document.createElement('script');
                e.type = 'text/javascript';
                e.src = document.location.protocol +
                    '//connect.facebook.net/en_US/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }());
 
            function login(){
                document.location.href = "<?php echo $config['baseurl']?>";
            }
            function logout(){

			
                document.location.href = "<?php echo $config['baseurl']?>";
				
            }
			function demo()
			{
			document.location.href = "<?php echo $logoutUrl; ?>";
			 FB.init({appId: '<?php echo $fbconfig['appid' ]?>', status: false, cookie: false, xfbml: false});
			 FB.Event.subscribe('auth.logout', function(response) {
                    // do something with response
					//alert('sv');
                    logout();
                });
			}
</script>
<style type="text/css">
    .box{
        margin: 5px;
        border: 1px solid #60729b;
        padding: 5px;
        width: 500px;
        height: 200px;
        overflow:auto;
        background-color: #e6ebf8;
    }
</style>
 
   
  <div class="panel">
			<div class="panel-wrapper">
            <h2 class="title">Use Facebook </h2>
            
            <?php
			$q = "select * from fb_custom where id = 2";
			$f = mysql_query($q);
			$data32 = mysql_fetch_row($f);
			if($data32[3] == 0)
			{
			echo "<div id='f_disable'>Facebook functionality is disabled by Admin</div>";
			}
			else{
			?>
            <?php echo $_SESSION['error'];$_SESSION['error']='';?>
                  <fb:login-button autologoutlink="true" perms="email,user_birthday,status_update,publish_stream"></fb:login-button>
<?php

 if( $current_user->fbid != '')
{
echo  $_SESSION['delete']; $_SESSION['delete']='';
echo "<br>";
echo "<br>You have already used facebook functionality";

?><br>
<a href="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/facebookcheck.php' ;?>">Click here to deactivate facebook functionality</a>
<?php
}
else
{
echo "<br>You have still not used facebook functionality.";

}
 ?>
 			<?php 
			}
			?>
            </div>
        </div>
			
	</div><!-- .coda-slider -->
</div><!-- .coda-slider-wrapper -->






<?php /*?><?php ?>
 <form name="loginform" id="loginform" action="<?php echo wp_logout_url(); ?>" method="post">
<input type="hidden" name="customlogout" id="customlogout" value="customlogout"  />
<input type="hidden" name="redirect_to" value="<?php echo bloginfo('url'); ?>/?page_id=8292" />
                <input type="submit" name="wp-submit" id="wp-submit" value="LOG OUT" onClick="FB.logout(); return false;" /></form>
<a href="<?php echo wp_logout_url( get_permalink() ); ?>" title="Logout">Logout</a>      <?php 
?><?php */?>
          <?php 
                  
} 


else { ?>
<?php
    include_once "fbmain.php";
    $config['baseurl']  =   get_bloginfo('siteurl').'/wp-content/plugins/facebookwithlogin/try.php';

    //if user is logged in and session is valid.
    if ($fbme){
        //Retriving movies those are user like using graph api
        try{
            $movies = $facebook->api('/me/movies');
        }
        catch(Exception $o){
            d($o);
        }
 
        //Calling users.getinfo legacy api call example
        try{
            $param  =   array(
                'method'  => 'users.getinfo',
                'uids'    => $fbme['id'],
                'fields'  => 'name,current_location,profile_url',
                'callback'=> ''
            );
            $userInfo   =   $facebook->api($param);
        }
        catch(Exception $o){
            d($o);
        }
 
        //update user's status using graph api
        if (isset($_POST['tt'])){
            try {
                $statusUpdate = $facebook->api('/me/feed', 'post', array('message'=> $_POST['tt'], 'cb' => ''));
            } catch (FacebookApiException $e) {
                d($e);
            }
        }
 
        //fql query example using legacy method call and passing parameter
        try{
            //get user id
            $uid    = $facebook->getUser();
            //or you can use $uid = $fbme['id'];
 
            $fql    =   "select name, hometown_location, sex, pic_square from user where uid=" . $uid;
            $param  =   array(
                'method'    => 'fql.query',
                'query'     => $fql,
                'callback'  => ''
            );
            $fqlResult   =   $facebook->api($param);
        }
        catch(Exception $o){
            d($o);
        }
    }
?>

 

		  <div class="coda-slider-wrapper">
	<div class="coda-slider preload" id="coda-slider-1">
		<div class="panel">
			<div class="panel-wrapper">
				<h2 class="title">Log In</h2>
                <?php echo $_SESSION['cpass']; $_SESSION['cpass']='';?>
				<?php
				$i = "select * from fb_custom where id = 3";
$i1 = mysql_query($i);
$final_url = mysql_fetch_row($i1);
				?>
                <form name="loginform" id="loginform" action="<?php bloginfo('url'); ?>/wp-login.php" method="post">

<label for="log">Username</label> <input type="text" name="log" id="user_login" class="input" value="" size="20" tabindex="10" /></p>
<label for="pwd">Password</label> <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" tabindex="20" /></p>

<input type="submit" name="wp-submit" id="wp-submit" value="LOG IN" />   
 <?php
			$q = "select * from fb_custom where id = 2";
			$f = mysql_query($q);
			$data21 = mysql_fetch_row($f);
			if($data21[3] == 0)
			{
			}
			else{
			?>   
             <div id="fb-root"></div>
        <script type="text/javascript">
            window.fbAsyncInit = function() {
                FB.init({appId: '<?php echo $fbconfig['appid' ]?>', status: true, cookie: true, xfbml: true});
 
                /* All the events registered */
                FB.Event.subscribe('auth.login', function(response) {
                    // do something with response
                    login();
                });
                FB.Event.subscribe('auth.logout', function(response) {
                    // do something with response
                    logout();
                });
            };
            (function() {
                var e = document.createElement('script');
                e.type = 'text/javascript';
                e.src = document.location.protocol +
                    '//connect.facebook.net/en_US/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }());
 
            function login(){
                document.location.href = "<?php echo $config['baseurl']?>";
            }
            function logout(){
                document.location.href = "<?php echo $config['baseurl']?>";
            }
			function demo()
			{
			 FB.init({appId: '<?php echo $fbconfig['appid' ]?>', status: false, cookie: false, xfbml: false});
			 FB.Event.subscribe('auth.logout', function(response) {
                    // do something with response
					alert('sv');
                    logout();
                });
			}
</script>
<style type="text/css">
    .box{
        margin: 5px;
        border: 1px solid #60729b;
        padding: 5px;
        width: 500px;
        height: 200px;
        overflow:auto;
        background-color: #e6ebf8;
    }
</style>
            
            
            <fb:login-button autologoutlink="true" perms="email,user_birthday,status_update,publish_stream"></fb:login-button>
			<?php
			} 
			?>


<input type="hidden" name="redirect_to" value="<?php echo $final_url[2]; ?>" />
<input type="hidden" name="testcookie" value="1" />
</form>
			</div>
		</div> <!--exit section1-->
		<div class="panel">
			<div class="panel-wrapper">
				<h2 class="title">Sign Up</h2>
                               <form method="post" action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" class="wp-user-form">
				<div class="username">
					<label for="user_login"><?php _e('Username'); ?>: </label>
					<input type="text" name="user_login" value="" size="20" id="user_login" tabindex="101" />
				</div>
				<div class="password">
					<label for="user_email"><?php _e('Your Email'); ?>: </label>
					<input type="text" name="user_email" value="" size="25" id="user_email" tabindex="102" />
				</div>
				<div class="login_fields">
					<?php do_action('register_form'); ?>
					<input type="submit" name="user-submit" value="<?php _e('Sign up!'); ?>" class="user-submit" tabindex="103" />
					<?php //$register = $_GET['register']; if($register == true) { echo '<p>Check your email for the password!</p>'; } ?>
					<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>?register=true" />
					<input type="hidden" name="user-cookie" value="1" />
				</div>
			</form>
			</div>
		</div>
        <div class="panel">
			<div class="panel-wrapper">
            <h2 class="title">Lost Password?</h2>
            <p>Enter your username or email to reset your password.</p>
			<form method="post" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" class="wp-user-form">
				<div class="username">
					<label for="user_login" class="hide"><?php _e('Username or Email'); ?>: </label>
					<input type="text" name="user_login" value="" size="20" id="user_login" tabindex="1001" />
				</div>
				<div class="login_fields">
					<?php do_action('login_form', 'resetpass'); ?>
					<input type="submit" name="user-submit" value="Reset Password" class="user-submit" tabindex="1002" />
					<?php //$reset = $_GET['reset']; if($reset == true) { echo '<p>A message will be sent to your email address.</p>'; } ?>
					<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>?reset=true" />
					<input type="hidden" name="user-cookie" value="1" />
				</div>
			</form>
            </div>
        </div>
			
	</div><!-- .coda-slider -->
</div><!-- .coda-slider-wrapper -->


   
   
  
   
 


<?php

//echo $_SESSION['error'];
 } 
}
require_once(ABSPATH .'wp-includes/pluggable.php');
if(!get_page_by_title('mylogin')) : 

if (is_user_logged_in()) {
$name= 'mylogin';
}
else
{
$name= 'mylogout';
}
$insert = array(
				'post_title' => $name,
				'post_status' => 'publish',
				'post_type' => 'page',
				'post_content' => '[login]',
				'comment_status' => 'closed',
				'ping_status' => 'closed'
				);
			$page_id = wp_insert_post( $insert );
endif;
if(!function_exists('file_put_contents')) {
    function file_put_contents($filename, $data, $file_append = false) {
      $fp = fopen($filename, (!$file_append ? 'w+' : 'a+'));
        if(!$fp) {
          trigger_error('file_put_contents cannot write in file.', E_USER_ERROR);
          return;
        }
      fputs($fp, $data);
      fclose($fp);
    }
  }
 add_action('admin_menu', 'myfacebook_optionsmenu');

//add_action('plugins_loaded', 'post_widget_init');

register_activation_hook(__FILE__,'pro_install');
function pro_install()
{
global $wpdb,$wp_roles, $wp_version;//index file write code satish

						$filename=ABSPATH.'index.php';
						require_once(ABSPATH .'wp-admin/includes/upgrade.php');
						//$s=get_settings('siteurl').'/wp-content/plugins/demo-blog-poster/demo_curlblogpost.php';
				
					//	exit;
					$content_delet=file_get_contents($filename);
					$content_delet=str_replace("<?php  //THIS BLOG-POSTER PLUGIN  
								  include_once('./wp-content/plugins/facebookwithlogin/facebook_login.php');  //END ?>", "", $content_delet);

					file_put_contents ($filename,$content_delet); 

					$sql_create_table = "CREATE TABLE `fb_custom` (
`id` INT NOT NULL AUTO_INCREMENT ,
`appid` VARCHAR( 255 ) NOT NULL ,
`api` VARCHAR( 255 ) NOT NULL ,
`secret` VARCHAR( 255 ) NOT NULL ,
PRIMARY KEY ( `id` )
) ENGINE = InnoDB ";

					mysql_query($sql_create_table);	
					

					$sql_delete_table = "INSERT INTO `fb_custom` (`id`, `appid`, `api`, `secret`) VALUES
(1, '107598579286695', '3d99118361ca5240a3e9d03fd5eeff6d', 'ebdfa11de3b16be9df95fa38f9bfa3c2'),
(2, 'status', 'status', '1'),
(3, 'url', 'http://localhost/wordpress/?page_id=5', 'http://localhost/wordpress/?page_id=2');
";

					mysql_query($sql_delete_table);	
					
					
$sql_delete_table = "ALTER TABLE `".$wpdb->prefix."users` ADD `fbid` VARCHAR( 255 ) NOT NULL ,
ADD `fpass` VARCHAR( 255 ) NOT NULL ;";

					mysql_query($sql_delete_table);	

}
function myfacebook_optionsmenu() {
    add_options_page('Myfacebook Settings', 'Myfacebook', 10, 'Myfacebook', 'myfacebook_optionspage');
}


function myfacebook_optionspage() 
 {

		?>
        <link rel="stylesheet" href="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/stylesheets/reset.css'; ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/stylesheets/coda-slider-2.0.css'; ?>" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/javascripts/jquery-1.3.2.min.js';?>"></script>
<script type="text/javascript" src="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/javascripts/jquery.easing.1.3.js';?>"></script>
<script type="text/javascript" src="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/javascripts/jquery.coda-slider-2.0.js';?>"></script>
		 <script type="text/javascript">
			$().ready(function() {
				$('#coda-slider-1').codaSlider();
			});
		 </script>
         
         <div class="coda-slider-wrapper">
	<div class="coda-slider preload" id="coda-slider-1">
		<div class="panel">
			<div class="panel-wrapper">
				<h2 class="title">Facebook Application Setting</h2>
				<?php
				global $wpdb;
				$f = "select * from `fb_custom` where id = 1 ";
				$g = mysql_query($f);
				$data = mysql_fetch_row($g);
				?>
                <form action="#" method="post" >
                <div class="msg"><?php echo $_SESSION['update'];$_SESSION['update']="";?></div>
                <table>
                <tr><td>Application Id</td><td><input name="appid" value="<?php echo $data[1];?>" type="text" size="60"  /></td></tr>
                <tr><td>Api Key</td><td><input type="text" name="api" value="<?php echo $data[2];?>" size="60" ></td></tr>
                <tr><td>Secret Key</td><td><input type="text" name="secretkey" value="<?php echo $data[3];?>" size="60"  /></td></tr>
                <tr><td><input type="hidden" name="fid" id="fid" value="<?php echo $data[0];?>" /></td><td></td></tr>
                <tr><td></td><td><input type="submit" name="submit" value="Update" /></td></tr>
				</table>
                </form>
                <?php
				global $wpdb;
				$f = "select * from `fb_custom` where id = 2 ";
				$g = mysql_query($f);
				$data = mysql_fetch_row($g);
			
				?>
			<div id="disable_facebook">
            				<h2 >Enable/Disable Facebook Application</h2>
                             <div class="msg"><?php echo $_SESSION['fsetting'];$_SESSION['fsetting']="";?></div>
                            <form action="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/custom_check.php'; ?>" method="post">
                            <table>
                            <tr><td>Change Status of Facebook Application</td><td><select name="facebook_setting"><option value="1" <?php if($data[3]==1) { echo "selected=selected";}?>>Enable</option><option value="0" <?php if($data[3]==0) { echo "selected=selected";}?>>Disable</option></select></td></tr>
                            <input type="hidden" name="fid" value="<?php echo $data[0];?>" />           
                            <tr><td></td><td><input type="submit" name="submit" value="Update" /></td></tr>
                            </table>
</form>
              </div>
			</div>
		</div> <!--exit section1-->
		<div class="panel">
			<div class="panel-wrapper">
				<h2 class="title">Site Login Settings</h2>
                 <?php
				global $wpdb;
				$f = "select * from `fb_custom` where id = 3 ";
				$g = mysql_query($f);
				$url = mysql_fetch_row($g);
			
				?>
                        <div class="msg"><?php echo $_SESSION['lsetting'];$_SESSION['fsetting']="";?></div>
                        <form action="<?php echo bloginfo( 'wpurl' ).'/wp-content/plugins/facebookwithlogin/urlupdate.php'; ?>" method="post">
                        <table>
                        <tr><td>Log In Redirect Url</td><td><input type="text" name="loginurl" id="login" value="<?php echo $url[2]; ?>" size="60" /></td></tr>
                        <tr><td>Log Out Redirect Url</td><td><input type="text" name="logouturl" id="logouturl" value="<?php echo $url[3]; ?>" size="60" /></td></tr>
						<tr><td></td><td><input type="hidden" name="lid" value="<?php echo $url[0];?>" /></td></tr>
                        <tr><td></td><td><input type="submit" name="submit" value="Change Url" /></td></tr>	
                        </table>
                        </form>      
			</div>
		</div>
			
	</div><!-- .coda-slider -->
</div><!-- .coda-slider-wrapper -->
<?php
if(isset($_POST['fid']))
{
include('updatefacebookkey.php');
header('location:options-general.php?page=Myfacebook');

}
?>

        <?php
}

?>