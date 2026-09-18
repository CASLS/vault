var auth2; // The Sign-In object.
var googleUser; // The current user.
var gUser = null;
var yiiAuth = false;

var startApp = function() {
	  gapi.load('auth2', function(){
	    // Retrieve the singleton for the GoogleAuth library and set up the client.
	  auth2 = gapi.auth2.init({
	      client_id: 'YOUR_GOOGLE_OAUTH_CLIENT_ID.apps.googleusercontent.com',
	      cookiepolicy: 'single_host_origin',
	      // Request scopes in addition to 'profile' and 'email'
	      //scope: 'additional_scope'
	    }).then(function(){
	    		auth2 = gapi.auth2.getAuthInstance();
			if($("#googleSignInBtn").length > 0){
				attachSignin(document.getElementById('googleSignInBtn'));
			}
	    });
	  });
};	

function onSignIn(googleUser) {
	var profile = googleUser.getBasicProfile();

	gUser = googleUser;
	//Check if account exists yet
	//If not, prompt to accept terms of use, then create new account
	$.ajax({
		method: "GET",
		url: "/google-auth/check-for-account",
		data: {email: profile.getEmail()}
	})
	.done(function( json ) {
		//1 == user was NOT found
		if(json.returnCode == 1){
			//prompt to accept terms of use
			gSignInWithToken();
		}else{
			//Else, the account exists so log in.
			//Verify the sign in on the server-side	
			gSignInWithToken();
		}
	});
	
}

function gSignInWithToken(){
	var id_token = gUser.getAuthResponse().id_token;
	if(id_token != null){
		$.ajax({
			method: "POST",
			url: "/google-auth/token-sign-in",
			data: {id_token: id_token}
		})
		.done(function( json ) {
			if(json.returnCode == 1){
				alert(json.returnCodeDescription);
			}else{
				location.reload();
			}
		});
	}
}

function signOut() {
	auth2 = gapi.auth2.getAuthInstance();
	if(auth2 != null){
		auth2.signOut().then(function () {
			console.log('User signed out.');
		});
	}
}

function onLoad() {
	gapi.load('auth2', function() {
		gapi.auth2.init();
	});
}

function attachSignin(element) {
  auth2.attachClickHandler(element, {},
      function(googleUser) {
	  	onSignIn(googleUser);
      }
  		, function(error) {
        console.log(JSON.stringify(error, undefined, 2));
      });
}