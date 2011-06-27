<?php
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
session_start();
 $id = $_POST['id'];

 $newpass=$_POST['new'];
 
 $pass = wp_hash_password($newpass);
 global $wpdb;
 $q = "UPDATE $wpdb->users SET `user_pass` = '".$pass."' WHERE `ID` = '".$id."'";
 mysql_query($q);
 $_SESSION['cpass'] = "Your Password Updated Successfully.Please Login again";
 unset($_SESSION['log_with_fb']);
 header('location:'.get_bloginfo('siteurl').'/?page_id='.wt_get_ID_by_page_name('mylogin').'#1');
?>
