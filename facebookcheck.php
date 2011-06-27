<?php
require_once('../../../wp-config.php');
require_once('../../../wp-load.php');
require_once('../../../wp-includes/class-phpass.php');
require_once( '../../../wp-includes/pluggable.php');
require_once( '../../../wp-includes/query.php');
require_once( '../../../wp-includes/general-template.php');
require_once( '../../../wp-includes/theme.php');

session_start();
	global $wpdb;
global $current_user;
      get_currentuserinfo();
function wt_get_ID_by_page_name($page_name)
{
	global $wpdb;
	$page_name_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = '".$page_name."'");
	return $page_name_id;
}

 $q = "UPDATE  $wpdb->users SET `fbid` = '',`fpass`='' WHERE `ID` = '".$current_user->ID."'";
 mysql_query($q);
 
 $_SESSION['delete'] = "Your Facebook Functionality deactivated successfully";
 header('location:'.get_bloginfo('siteurl').'/?page_id='.wt_get_ID_by_page_name('mylogin').'#3');
?>