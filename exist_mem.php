<?php
session_start();


require_once('../../../wp-config.php');
require_once('../../../wp-load.php');
require_once('../../../wp-includes/class-phpass.php');
require_once( '../../../wp-includes/pluggable.php');
require_once( '../../../wp-includes/query.php');
require_once( '../../../wp-includes/general-template.php');
require_once( '../../../wp-includes/theme.php');
function wt_get_ID_by_page_name($page_name)
{
	global $wpdb;
	$page_name_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$page_name."'");
	return $page_name_id;
}

$log=$_POST['log'];
$delete_id=$_POST['id'];

//echo $log;
$pwd=$_POST['pwd'];
//echo $pwd;
$query_uname="select * from $wpdb->users where user_login='".$log."'";
$result_uname = mysql_query($query_uname);
$num_rows_uname = mysql_num_rows($result_uname);
if($num_rows_uname==1)
{
while ($user_data = mysql_fetch_array($result_uname))
{
$user_name=$user_data['user_login'];
$user_password=$user_data['user_pass'];
$user_id=$user_data['ID'];

}
$result_pass=wp_check_password($pwd,$user_password,$user_id);
$result_pass_2=md5($pwd);
if($result_pass_2==$user_password || $result_pass)
{
 global $wpdb;
 $fbid=$_SESSION['loginstatus'];
 $q = "UPDATE $wpdb->users SET `fbid` = '".$fbid."' WHERE `ID` = '".$user_id."'";
 mysql_query($q);
 $delete="Delete from $wpdb->users where ID = '".$delete_id."'";
 mysql_query($delete);
 $_SESSION['cpass'] = "Now yor facebook detail is connected with the site.";
 unset($_SESSION['log_with_fb']);
  header('location:'.get_bloginfo('siteurl').'/?page_id='.wt_get_ID_by_page_name('mylogin').'#1');
}
else
{
echo "Username and/or password is incorrect";
}
}
else
{
echo "Username and/or password is incorrect";
}


 
?>
