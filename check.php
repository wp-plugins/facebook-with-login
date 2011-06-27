<?php

$data = get_option('My_Login');

 if($data['option1']==1)	{	
	

	//echo "login";
	$fbme='';
	 include_once "fbmain.php";
    $config['baseurl']  =   get_bloginfo('siteurl').'/wp-content/plugins/facebookwithlogin/try.php';

    //if user is logged in and session is valid.
    if ($fbme){
        //Retriving movies those are user like using graph api
        try{
            $movies = $facebook->api('/me/movies');
        }
        catch(Exception $o){
            d($o);
        }
 
        //Calling users.getinfo legacy api call example
        try{
            $param  =   array(
                'method'  => 'users.getinfo',
                'uids'    => $fbme['id'],
                'fields'  => 'name,current_location,profile_url',
                'callback'=> ''
            );
            $userInfo   =   $facebook->api($param);
        }
        catch(Exception $o){
            d($o);
        }
 
        //update user's status using graph api
        if (isset($_POST['tt'])){
            try {
                $statusUpdate = $facebook->api('/me/feed', 'post', array('message'=> $_POST['tt'], 'cb' => ''));
            } catch (FacebookApiException $e) {
                d($e);
            }
        }
 
        //fql query example using legacy method call and passing parameter
        try{
            //get user id
            $uid    = $facebook->getUser();
            //or you can use $uid = $fbme['id'];
 
            $fql    =   "select name, hometown_location, sex, pic_square from user where uid=" . $uid;
            $param  =   array(
                'method'    => 'fql.query',
                'query'     => $fql,
                'callback'  => ''
            );
            $fqlResult   =   $facebook->api($param);
        }
        catch(Exception $o){
            d($o);
        }
    }
	
	?>
    <div id="fb-root"></div>
        <script type="text/javascript">
            window.fbAsyncInit = function() {
                FB.init({appId: '<?php echo $fbconfig['appid' ]?>', status: true, cookie: true, xfbml: true});
 
                /* All the events registered */
                FB.Event.subscribe('auth.login', function(response) {
                    // do something with response
                    login();
                });
                FB.Event.subscribe('auth.logout', function(response) {
                    // do something with response
                    logout();
                });
            };
            (function() {
                var e = document.createElement('script');
                e.type = 'text/javascript';
                e.src = document.location.protocol +
                    '//connect.facebook.net/en_US/all.js';
                e.async = true;
                document.getElementById('fb-root').appendChild(e);
            }());
 
            function login(){
                document.location.href = "<?php echo $config['baseurl']?>";
            }
            function logout(){
                document.location.href = "<?php echo $config['baseurl']?>";
            }
			function demo()
			{
			 FB.init({appId: '<?php echo $fbconfig['appid' ]?>', status: false, cookie: false, xfbml: false});
			 FB.Event.subscribe('auth.logout', function(response) {
                    // do something with response
					alert('sv');
                    logout();
                });
			}
</script>
<style type="text/css">
    .box{
        margin: 5px;
        border: 1px solid #60729b;
        padding: 5px;
        width: 500px;
        height: 200px;
        overflow:auto;
        background-color: #e6ebf8;
    }
</style>
 <?php echo $_SESSION['cpass']; $_SESSION['cpass']='';?>
				<?php
			 $i = "select * from fb_custom where id = 3";
				$i1 = mysql_query($i);
				$final_url = mysql_fetch_row($i1);
				?>
                <form name="loginform" id="loginform" action="<?php bloginfo('url'); ?>/wp-login.php" method="post">

<label for="log">Username</label> <input type="text" name="log" id="user_login" class="input" value="" size="20" tabindex="10" /></p>
<label for="pwd">Password</label> <input type="password" name="pwd" id="user_pass" class="input" value="" size="20" tabindex="20" /></p>

<input type="hidden" name="redirect_to" value="<?php echo $final_url[2]; ?>" />
<input type="hidden" name="testcookie" value="1" />
<input type="submit" name="wp-submit" id="wp-submit" value="LOG IN" />   

 <?php
			$q = "select * from fb_custom where id = 2";
			$f = mysql_query($q);
			$data123 = mysql_fetch_row($f);
			if($data123[3] == 0)
			{
			}
			else{
			?>

      <fb:login-button autologoutlink="true" perms="email,user_birthday,status_update,publish_stream"></fb:login-button>
<?php } ?>


</form>


    <?php
				if($data['option4'] == 1)
				{
				echo "<a href='".get_bloginfo('siteurl')."/?page_id=".wt_get_ID_by_page_name('mylogin')."#2'>-Register</a>";
				}
				
				if($data['option5'] == 1)
				{
				echo "<a href='".get_bloginfo('siteurl')."/?page_id=".wt_get_ID_by_page_name('mylogin')."#3'>-Lost Password?</a>";
				}
				

}	//end of login
		
		if($data['option1']==2)	{ 
		
		?>
         <form method="post" action="<?php echo site_url('wp-login.php?action=register', 'login_post') ?>" class="wp-user-form">
				<div class="username">
					<label for="user_login"><?php _e('Username'); ?>: </label>
					<input type="text" name="user_login" value="" size="20" id="user_login" tabindex="101" />
				</div>
				<div class="password">
					<label for="user_email"><?php _e('Your Email'); ?>: </label>
					<input type="text" name="user_email" value="" size="25" id="user_email" tabindex="102" />
				</div>
				<div class="login_fields">
					<?php do_action('register_form'); ?>
					<input type="submit" name="user-submit" value="<?php _e('Sign up!'); ?>" class="user-submit" tabindex="103" />
					<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>?register=true" />
					<input type="hidden" name="user-cookie" value="1" />
				</div>
			</form>
        <?php
			if($data['option3'] == 1)
				{
				echo "<a href='".get_bloginfo('siteurl')."/?page_id=".wt_get_ID_by_page_name('mylogin')."#1'>-Log In</a>";
				}
				
				if($data['option5'] == 1)
				{
				echo "<a href='".get_bloginfo('siteurl')."/?page_id=".wt_get_ID_by_page_name('mylogin')."#3'>-Lost Password?</a>";
				}
		
			} // end of register
			
			if($data['option1']==3)	{
			
			?>
            <form method="post" action="<?php echo site_url('wp-login.php?action=lostpassword', 'login_post') ?>" class="wp-user-form">
				<div class="username">
					<label for="user_login" class="hide"><?php _e('Username or Email'); ?>: </label>
					<input type="text" name="user_login" value="" size="20" id="user_login" tabindex="1001" />
				</div>
				<div class="login_fields">
					<?php do_action('login_form', 'resetpass'); ?>
					<input type="submit" name="user-submit" value="<?php _e('Reset my password'); ?>" class="user-submit" tabindex="1002" />
					<?php //$reset = $_GET['reset']; if($reset == true) { echo '<p>A message will be sent to your email address.</p>'; } ?>
					<input type="hidden" name="redirect_to" value="<?php echo $_SERVER['REQUEST_URI']; ?>?reset=true" />
					<input type="hidden" name="user-cookie" value="1" />
				</div>
			</form>
            <?php
				if($data['option3'] == 1)
				{
				echo "<a href='".get_bloginfo('siteurl')."/?page_id=".wt_get_ID_by_page_name('mylogin')."#1'>-Login</a>";
				}
				
				if($data['option4'] == 1)
				{
				echo "<a href='".get_bloginfo('siteurl')."/?page_id=".wt_get_ID_by_page_name('mylogin')."#2'>-Register</a>";
				}
				}//end of lostpassword
?>