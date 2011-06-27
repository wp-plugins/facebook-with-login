<?php
require_once('../../../wp-config.php');
require_once('../../../wp-load.php');
require_once('../../../wp-includes/class-phpass.php');
require_once( '../../../wp-includes/pluggable.php');
require_once( '../../../wp-includes/query.php');
require_once( '../../../wp-includes/general-template.php');
require_once( '../../../wp-includes/theme.php');

session_start();
 $id = $_POST['id'];
 global $wpdb;
function wt_get_ID_by_page_name($page_name)
{
	global $wpdb;
	$page_name_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$page_name."'");
	return $page_name_id;
}
 $fname =$_POST['fname'];
 $lname = $_POST['lname'];
 $email = $_POST['email'];
 $url = $_POST['url'];
 $binfo = $_POST['binfo'];
 $q = "UPDATE $wpdb->users SET `user_url` = '".$url."',`user_email`='".$email."' WHERE `ID` = '".$id."'";
 mysql_query($q);
 $q = "UPDATE $wpdb->usermeta SET `meta_value` = '".$fname."' WHERE `user_id` = '".$id."' AND `meta_key`='first_name'";
 mysql_query($q);
 $q = "UPDATE $wpdb->usermeta SET `meta_value` = '".$lname."' WHERE `user_id` = '".$id."' AND `meta_key`='last_name'";
 mysql_query($q);
 $q = "UPDATE $wpdb->usermeta SET `meta_value` = '".$binfo."' WHERE `user_id` = '".$id."' AND `meta_key`='description'";
 mysql_query($q);
 $_SESSION['profile'] = "Your Profile Updates Successfully";
 header('location:'.get_bloginfo('siteurl').'/?page_id='.wt_get_ID_by_page_name('mylogin').'#2');
?>