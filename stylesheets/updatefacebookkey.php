<?php
session_start();
echo $q = "UPDATE `".$wpdb->prefix."fb_custom` SET `appid` = '".$_POST['appid']."',`api` = '".$_POST['api']."',`secret` = '".$_POST['secretkey']."' WHERE `id` = '".$_POST['fid']."' LIMIT 1 ;";
$f = mysql_query($q);
echo mysql_num_rows($f);
$_SESSION['update']="Setting Updated Successfully";
//header('location:options-general.php?page=Myfacebook#1');
?>