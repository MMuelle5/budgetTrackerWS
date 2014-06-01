<?php

	include_once('config/config.php');
	//  start session
	if (!isset($_SESSION)) {
	    session_start();
		$_SESSION['clientid'] = $client_id;
	}
	// if (session_status() == PHP_SESSION_NONE) {
		// session_start();
	    // $_SESSION['clientid'] = $client_id;
	// }
	// CSRF Counter-measure
	$token = md5(uniqid(rand(), TRUE));
	$_SESSION['state'] = $token;
	$gPlusWhenLogout = '	<div id="gSignInWrapper">
							<div id="customBtn">
								<a onclick="gapi.auth.signIn({\'clientid\' : \'' . $_SESSION['clientid'] . '\',\'cookiepolicy\' : \'single_host_origin\',
\'callback\' : \'signinCallback\',\'requestvisibleactions\' : \'http://schemas.google.com/AddActivity\',\'immediate\': true, \'scope\' : \'https://www.googleapis.com/auth/plus.login\'}); $(\'#customBtn\').hide();">+Sign In</a>
							</div>
						</div>';
	
?>
<html>
	<head>
		<script src="js/jquery-2.1.0.min.js"></script>
<!-- 		<script src="js/jquery.mobile-1.0.1.min.js"></script> -->
		<script type="text/javascript">
	  	window.___gcfg = {lang: 'de'};
			(function() {
		    		var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
		    		po.src = 'https://apis.google.com/js/platform.js';
		    		var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
			})();
			
		function goBack(event) {
		    event.preventDefault();
		    history.back(1);
		}
		</script>
		
		<?php
		if (!isset($_SESSION['logout'])) {
			$gScript = 'po.src = \'https://plus.google.com/js/client:plusone.js?onload=render\';';
		} else {
			$gScript = 'po.src = \'https://plus.google.com/js/client:plusone.js\';';
		}
		echo '<script type="text/javascript">
    		(function () {
      			var po = document.createElement(\'script\');
      			po.type = \'text/javascript\';
      			po.async = true;
      			' . $gScript . '
				var s = document.getElementsByTagName(\'script\')[0];
      			s.parentNode.insertBefore(po, s);
    		})();
			
			function render() {
		    	gapi.signin.render(\'customBtn\', {
		      		\'callback\': \'signinCallback\',
		      		\'clientid\': \'' . $_SESSION['clientid'] . '\',
		      		\'cookiepolicy\': \'single_host_origin\',
		      		\'requestvisibleactions\': \'http://schemas.google.com/AddActivity\',
		      		\'scope\': \'https://www.googleapis.com/auth/plus.login\',
		      		\'immediate\': true
		    	});
		  	}
			function signinCallback(authResult) {
				if (authResult[\'code\']) {
					$.post( "ajx/plus.php?storeToken",
						{ code: authResult[\'code\'], state: "' . $_SESSION['state'] . '"},
						function( data ) {
							$(\'#gPlus\').empty().append( data );
						}
					);
				}
			};

			function gvnSignOut() {
				$.post( "ajx/plus.php?logout", {state: "' . $_SESSION['state'] . '"},
				function( data ) {
					$(\'#gPlus\').empty().append( data );
				});
				gapi.auth.signOut();
			};
		</script>
		
      	
      	
      	
      	<style type="text/css">
			#customBtn {cursor: pointer;}
			#customBtn:hover {text-decoration: underline; cursor: hand;}
		</style>
      	';
		
		if (isset($_SESSION['access_token'])) {
			if (isset($_SESSION['logout'])) {
				$gPlus = $gPlusWhenLogout;
			} else {
				if (isset($_SESSION['id']))
				$gPlus = '<div id="gPlusNav">
					<a onclick="gvnSignOut(); return false;" href="#">Disconnect</a>   
					</div>';
			}
		} else {
			if (isset($_SESSION['logout'])) {
				$gPlus = $gPlusWhenLogout;
			} else {
				$gPlus = '<div id="gSignInWrapper">
		    				<div id="customBtn" class="customGPlusSignIn">
							<a>+Sign In</a>
						</div>
					</div>';
			}
		}
		?>
	</head>
	<body>		
		<h1>Bitte loggen Sie sich ein</h1>
		<br />
<?php 
echo "		<div id = \"gPlus\" style=\"border: 1px solid; padding: 10px\">
	$gPlus
		</div><br>
";
?>
<button type="button" onclick="goBack(event);">Zur&uuml;ck zur Applikation</button>
<!-- 	<button type="button" onclick="window.close()">Zur&uuml;ck zur Applikation</button> -->
	</body>
</html>