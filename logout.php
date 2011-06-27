<?php
if(isset($_POST['customlogout']))
{
 include_once "fbmain.php";
 echo $session = $facebook->getsession();
unset($fbme);
unset($session);
?>
 <script type="text/javascript">
 alert('sdhdr');
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
</script>
<?php
print_r($session);
unset($session);
 echo   $logoutUrl = $facebook->getLogoutUrl();
echo "helloworld";
$fbme = '';
unset($fbme);

$ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'http://m.facebook.com/logout.php?confirm=1');
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
            curl_setopt($ch, CURLOPT_NOBODY, TRUE); // remove body
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        echo     $head = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch); 
}

?>