<?php
session_start();

require_once('../../../wp-config.php');
require_once('../../../wp-load.php');
require_once('../../../wp-includes/class-phpass.php');
require_once( '../../../wp-includes/pluggable.php');
require_once( '../../../wp-includes/query.php');
require_once( '../../../wp-includes/general-template.php');
require_once( '../../../wp-includes/theme.php');

$id = $_POST['fid'];
$v = $_POST['facebook_setting'];

$q = "UPDATE `fb_custom` SET `secret` = '".$v."' WHERE `id` ='".$id."' LIMIT 1 ;";
mysql_query($q);
$_SESSION['fsetting']="Facebook Setting Updated successfully";
header('location:'.get_bloginfo('siteurl').'/wp-admin/options-general.php?page=Myfacebook');
?>