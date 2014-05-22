<?php
# Logging in with Google accounts requires setting special identity, so this example shows how to do it.
require 'openid.php';

session_start();

try {
    # Change 'localhost' to your domain name.
    $openid = new LightOpenID('localhost');
	
	if (!(isset($_SESSION['angemeldet']) && $_SESSION['angemeldet'] != '')) {
	    if(!$openid->mode) {
	    	echo isset($_GET['login']);
	        if(isset($_GET['login'])) {
	            $openid->identity = 'https://www.google.com/accounts/o8/id';
		        header('Location: ' . $openid->authUrl());
				$_SESSION['angemeldet'] = $openid->authUrl();
		        ?>
		        <script type="text/javascript">window.close();</script>
		        <?php
	        }
	?>
		<form action="?login" method="post">
		    <button>Login with Google</button>
		</form>
	<?php
	    } elseif($openid->mode == 'cancel') {
	        echo 'User has canceled authentication!';
	    } else {
	        ?>
	        <script type="text/javascript">window.close();</script>
	        <?php
	    }
	}
	echo '<a href="javascript:window.close();">zu</a>';
	
	
} catch(ErrorException $e) {
    echo $e->getMessage();
}
