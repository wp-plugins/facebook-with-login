<?php
session_start();
 $q = "UPDATE `fb_custom` SET `appid` = '".$_POST['appid']."',`api` = '".$_POST['api']."',`secret` = '".$_POST['secretkey']."' WHERE `id` = '".$_POST['fid']."' LIMIT 1 ;";
$f = mysql_query($q);
$_SESSION['update']="Setting Updated Successfully";
header('location:options-general.php?page=Myfacebook');
?>