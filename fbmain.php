<?php
require_once('../../../wp-config.php');
/*$con = mysql_connect('localhost','root','');
mysql_select_db('facebook',$con);
*/

ini_set('display_errors',1);


 $q = "select * from fb_custom where id = 1";
$q1 = mysql_query($q);
$data123 = mysql_fetch_row($q1);
$fbconfig['appid' ]  = "107598579286695";
    $fbconfig['api'   ]  = "3d99118361ca5240a3e9d03fd5eeff6d";
    $fbconfig['secret']  = "ebdfa11de3b16be9df95fa38f9bfa3c2";
	 $fbconfig['appid' ] = $data123[1];
	 $fbconfig['api'   ] = $data123[2];
 	 $fbconfig['secret'] = $data123[3];
    try{
        include_once "src/facebook.php";
    }
    catch(Exception $o){
        echo '<pre>';
        print_r($o);
        echo '</pre>';
    }
    // Create our Application instance.
    $facebook = new Facebook(array(
      'appId'  => $fbconfig['appid'],
      'secret' => $fbconfig['secret'],
      'cookie' => true,
    ));
 
    // We may or may not have this data based on a $_GET or $_COOKIE based session.
    // If we get a session here, it means we found a correctly signed session using
    // the Application Secret only Facebook and the Application know. We dont know
    // if it is still valid until we make an API call using the session. A session
    // can become invalid if it has already expired (should not be getting the
    // session back in this case) or if the user logged out of Facebook.
    $session = $facebook->getSession();
 
    $fbme = null;
    // Session based graph API call.
    if ($session) {
      try {
        $uid = $facebook->getUser();
        $fbme = $facebook->api('/me');
      } catch (FacebookApiException $e) {
          d($e);
      }
    }
 
    function d($d){
        echo '<pre>';
        print_r($d);
        echo '</pre>';
    }
?>