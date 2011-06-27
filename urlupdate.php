<?php
session_start();

require_once('../../../wp-config.php');
require_once('../../../wp-load.php');
require_once('../../../wp-includes/class-phpass.php');
require_once( '../../../wp-includes/pluggable.php');
require_once( '../../../wp-includes/query.php');
require_once( '../../../wp-includes/general-template.php');
require_once( '../../../wp-includes/theme.php');

$id = $_POST['lid'];
$lurl = $_POST['loginurl'];
$lourl = $_POST['logouturl'];

$q = "UPDATE `fb_custom` SET `api`='".$lurl."',`secret` = '".$lourl."' WHERE `id` ='".$id."' LIMIT 1 ;";
mysql_query($q);
$_SESSION['lsetting']="Redirection Url Setting Updated successfully";
header('location:'.get_bloginfo('siteurl').'/wp-admin/options-general.php?page=Myfacebook#2');
?>