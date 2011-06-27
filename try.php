<?php
session_start();

ini_set('display_errors', 1);

include_once "fbmain.php";
global $wpdb;
$_SESSION['loginstatus'] =  $fbme['id'];




require_once('../../../wp-config.php');
require_once('../../../wp-load.php');
require_once('../../../wp-includes/class-phpass.php');
require_once( '../../../wp-includes/pluggable.php');
require_once( '../../../wp-includes/query.php');
require_once( '../../../wp-includes/general-template.php');
require_once( '../../../wp-includes/theme.php');

if($fbme['id'] != '') { 
		if (is_user_logged_in()) 
		{
		global $current_user;
		
			  get_currentuserinfo();
			   $pass = $fbme[first_name];
				 $q = "select * from $wpdb->users where fbid = '".$fbme['id']."'";
				$f = mysql_query($q);
				//echo mysql_num_rows($f);
				$data = mysql_fetch_row($f);
				if(mysql_num_rows($f) != 0)
				{
				$_SESSION['error'] = "You have already used this functionality<br>";
				
				echo "<script>window.location.href='".get_bloginfo('siteurl')."/?page_id=".wt_get_ID_by_page_name('mylogin')."#3'</script>";
				}
				else
				{
				$f = "UPDATE `$wpdb->users` SET `fbid` = '".$fbme['id']."',`fpass` = '".$pass."'  WHERE `ID` ='".$current_user->ID."'";
				mysql_query($f);
				
				 $i = "select * from fb_custom where id = 3";
				$i1 = mysql_query($i);
				$final_url = mysql_fetch_row($i1);
				   echo "<script type='text/javascript'>window.location.href='".$final_url[2]."'</script>";				
				}
		
		}
		
		else
		{

				$q = "select * from $wpdb->users where fbid = '".$fbme['id']."'";
				$f = mysql_query($q);
			//	echo mysql_num_rows($f);
				$data = mysql_fetch_row($f);
				if(mysql_num_rows($f) != 0)
				{
				global $user_ID;
				
				//header('http://localhost/wordpress/wp-content/plugins/facebookwithlogin/facebook_login.php');
				if($remember) $remember = "true";
				else $remember = "false";
				$login_data = array();
				 $login_data['user_login'] = $data['1'];
				 $login_data['user_password'] = $data['11'];
				$login_data['remember'] = $remember;
				$user_verify = wp_signon1( $login_data, true ); 
				 
				if ( is_wp_error($user_verify) ) 
				{
				   echo "<span class='error'>Invalid username or password. Please try again!</span>";
				   exit();
				 } else 
				 {	
				 $i = "select * from fb_custom where id = 3";
				$i1 = mysql_query($i);
				$final_url = mysql_fetch_row($i1);
				   echo "<script type='text/javascript'>window.location.href='".$final_url[2]."'</script>";
				   exit();
				 }
				 	 
				
				}
				else
				{
				 $pass = wp_hash_password($fbme[id]);
				 $sql1 = "INSERT INTO `$wpdb->users` (`ID` ,`user_login` ,`user_pass` ,`user_nicename` ,`user_email` ,`user_url` ,`user_registered` ,`user_activation_key` ,`user_status` ,`display_name`,`fbid`,`fpass`)VALUES (NULL , '$fbme[email]', '$pass', '$fbme[name]', '$fbme[email]', '$fbme[link]', '".date("m/d/y H:i:s",time())."', '', '0', '$fbme[first_name]',$fbme[id],'$fbme[first_name]')";
				 $_SESSION['log_with_fb']='true';

				$query1 = mysql_query($sql1);
				$last_id =  mysql_insert_id();
			
				$query3 = "INSERT INTO `$wpdb->usermeta` VALUES (NULL, $last_id, 'wp_capabilities', 'a:1:{s:13:\"administrator\";b:1;}');";
				mysql_query($query3); 
				$q = "select * from $wpdb->users where fbid = '".$fbme['id']."'";
				$f = mysql_query($q);
				echo mysql_num_rows($f);
				$data = mysql_fetch_row($f);
					global $user_ID;
				
				//header('http://localhost/wordpress/wp-content/plugins/facebookwithlogin/facebook_login.php');
				if($remember) $remember = "true";
				else $remember = "false";
				$login_data = array();
				echo $login_data['user_login'] = $data['1'];
				echo $login_data['user_password'] = $data['11'];
				$login_data['remember'] = $remember;
				$user_verify = wp_signon1( $login_data, true ); 
				 
				if ( is_wp_error($user_verify) ) 
				{
				   echo "<span class='error'>Invalid username or password. Please try again!</span>";
				   exit();
				 } else 
				 {	
 					$i = "select * from fb_custom where id = 3";
				$i1 = mysql_query($i);
				$final_url = mysql_fetch_row($i1);
				   echo "<script type='text/javascript'>window.location.href='".$final_url[2]."'</script>";
				   				   exit();
				 }
				 $_SESSION['success'] = 'You are successfully registered in our site.Username and password are sent to your mail id for our site';
				    $to = $fbme[email];
					$subject = "Your Registration in site";
					$message = "Hello '".$fbme[name]."', Thanks for registering to our site. Following details for access our sites are: Your Username is: ".$fbme[email]." Your Password is:".$fbme[id]."<br>";
					$message.="You can acees our site by this details or also by your facebook details";
					$from = 'nilesh4125@gmail.com';
					$headers = "From: $from";
					@mail($to,$subject,$message,$headers);		
				$i = "select * from fb_custom where id = 3";
				$i1 = mysql_query($i);
				$final_url = mysql_fetch_row($i1);
				   echo "<script type='text/javascript'>window.location.href='".$final_url[2]."'</script>";				
				}
			}

}
else
{
$i = "select * from fb_custom where id = 3";
				$i1 = mysql_query($i);
				$final_url = mysql_fetch_row($i1);
echo "<script>window.location.href='".wp_logout_url( $final_url[3] )."';</script>";

}


function wp_signon1( $credentials = '', $secure_cookie = '' ) {
	

	// TODO do we deprecate the wp_authentication action?
	do_action_ref_array('wp_authenticate', array(&$credentials['user_login'], &$credentials['user_password']));

	if ( '' === $secure_cookie )
		$secure_cookie = is_ssl();

	$secure_cookie = apply_filters('secure_signon_cookie', $secure_cookie, $credentials);

	global $auth_secure_cookie; // XXX ugly hack to pass this to wp_authenticate_cookie
	$auth_secure_cookie = $secure_cookie;

	add_filter('authenticate', 'wp_authenticate_cookie', 30, 3);
	remove_action('authenticate', 'wp_authenticate_username_password', 20);
add_filter( 'authenticate', 'my_custom_function', 10, 3 );
	$user = wp_authenticatenew($credentials['user_login'], $credentials['user_password']);

	if ( is_wp_error($user) ) {
		if ( $user->get_error_codes() == array('empty_username', 'empty_password') ) {
			$user = new WP_Error('', '');
		}

		return $user;
	}

	wp_set_auth_cookie($user->ID, $credentials['remember'], $secure_cookie);
	do_action('wp_login', $credentials['user_login']);
	return $user;
}

function my_custom_function( $user, $username, $password ){
 
     $user = get_userdatabylogin( $username );  //we don't really need this, but you might
 
     if( $password == $data[11]  ) {  //if the username is bob
 
        return $user;
 
     }
 
     return $user;
 
}
function wp_authenticatenew($username, $password) {
	$username = sanitize_user($username);
	$password = trim($password);
	
	$user = apply_filters('authenticate', null, $username, $password);
	
	
			//log_app("authenticate()", $data[1]);
	if ( $user == null ) {
		// TODO what should the error message be? (Or would these even happen?)
		// Only needed if all authentication handlers fail to return anything.
		$user = new WP_Error('authentication_failed', __('<strong>ERROR</strong>: Invalid username or incorrect password.'));
	}

	$ignore_codes = array('empty_username', 'empty_password');

	if (is_wp_error($user) && !in_array($user->get_error_code(), $ignore_codes) ) {
		do_action('wp_login_failed', $username);
	}

	return $user;

}

