<html>
  <head>
    <meta charset='utf-8' />
	<script src="js/jquery-2.1.0.min.js"></script>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/jquery.mobile-1.0.1.min.css"/>
  </head>
  <body>
    <!--Add a button for the user to click to initiate auth sequence -->
    
    	<h1>Login zu Google</h1>
	    <button id="authorize-button" type="button" onclick="handleAuthClick(event)" style="visibility: hidden">Einloggen</button>
	    <div id="authorized" style="visibility: hidden"><h2>Angemeldet als</h2></div>
	    <script type="text/javascript">
	      // Enter a client ID for a web application from the Google Developer Console.
	      // The provided clientId will only work if the sample is run directly from
	      // https://google-api-javascript-client.googlecode.com/hg/samples/authSample.html
	      // In your Developer Console project, add a JavaScript origin that corresponds to the domain
	      // where you will be running the script.
	      var clientId = '';
	
	      // Enter the API key from the Google Develoepr Console - to handle any unauthenticated
	      // requests in the code.
	      // The provided key works for this sample only when run from
	      // https://google-api-javascript-client.googlecode.com/hg/samples/authSample.html
	      // To use in your own application, replace this API key with your own.
	      var apiKey = '';
	
	      // To enter one or more authentication scopes, refer to the documentation for the API.
	      var scopes = 'https://www.googleapis.com/auth/plus.me';
	
	      // Use a button to handle authentication the first time.
	      function handleClientLoad() {
	        gapi.client.setApiKey(apiKey);
	        window.setTimeout(checkAuth,1);
	      }
	
	      function checkAuth() {
	        gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: true}, handleAuthResult);
	      }
	
	      function handleAuthResult(authResult) {
	        var authorizeButton = $("#authorize-button");
	        var authorized = $("#authorized");
	        if (authResult && !authResult.error) {
	          authorizeButton.css('visibility','hidden');
	          authorized.css('visibility','visible');
	          
	          makeApiCall(authResult["access_token"]);
	        } else {
	          authorizeButton.css('visibility','visible');
	        }
	      }
	
	      function handleAuthClick(event) {
	        gapi.auth.authorize({client_id: clientId, scope: scopes, immediate: false}, handleAuthResult);
	        return false;
	      }
	
	      // Load the API and make an API call.  Display the results on the screen.
	      function makeApiCall(access_token) {
	        gapi.client.load('plus', 'v1', function() {
	          var request = gapi.client.plus.people.get({
	            'userId': 'me'
	          });
	          request.execute(function(resp) {
	            var heading = document.createElement('h4');
	            var image = document.createElement('img');
	            image.src = resp.image.url;
	            heading.appendChild(image);
	            
	            heading.appendChild(document.createTextNode(resp.displayName));
	
	            document.getElementById('content').appendChild(heading);
	            
	            $.get("store.php",{access_token : access_token, id: resp.id},function(data){console.log(data);});
	          });
	        });
	      }
	      
		function goBack(event) {
		    event.preventDefault();
		    history.back(1);
		}
	    </script>
	    <script src="https://apis.google.com/js/client.js?onload=handleClientLoad"></script>
	    <div id="content"></div>
	    <button type="button" onclick="goBack(event);">Zur&uuml;ck zur Applikation</button>
    
  </body>
</html>