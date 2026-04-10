<?php
//------------------------------------------------------------------------------
// массив данных для обработки социальных сетей
//------------------------------------------------------------------------------
global $soc_net;
//..............................................................................
// для сети "facebook"
//..............................................................................
$soc_net['FB'] = array
	(
	'title'		=> 'FaceBook',
	'login'		=> 'facebook',
	'button'	=> array (
			'link'	=> 'https://www.facebook.com/dialog/oauth',
			'title'	=> 'OPENLOGIN_FB_TITLE',
			'class'	=> 'facebook',
			),

	'token' 	=> array (
			'param'	=> array (
			        'client_id' 	=> '172972799707883',
	        		'client_secret' => 'c648865ccc9c9af17610716a1f16005b',
				'response_type'	=> 'code',
				'scope' 	=> 'public_profile,email,publish_actions',
	        		'redirect_uri' 	=> REDIRECT_URI_SCRIPT."?".SOCIAL_SELECTOR."=FB"
				),
			'link'	=> 'https://graph.facebook.com/oauth/access_token',
			),

	'user'		=> array (
			'link'	=> 'https://graph.facebook.com/v2.6/me',
			),

	'post'		=> array (
			'link'	=> 'https://graph.facebook.com/v2.6/me/feed',
			),

	'enable'	=> 1,
	);

//..............................................................................
// для сети "twitter"
//..............................................................................
$soc_net['TW'] = array
	(
	'title'		=> 'Twitter',
	'login'		=> 'twitter',
	'button'	=> array (
			'link'	=> 'https://api.twitter.com/oauth/authorize',
			'title'	=> 'OPENLOGIN_TW_TITLE',
			'class'	=> 'twitter',
			),

	'token' 	=> array (
			'oauth_consumer_key' 	=> 'M6hDyVsMPpaCBmNL6aSey60bk',
			'oauth_consumer_secret' => '1EEK0iNVbXHEyWt5iqM83D4XFME0dHzQjTXcLj6eD9fEm4QEta',
			'oauth_nonce'		=> md5(uniqid(rand(), true)),
			'oauth_timestamp'	=> time(),
			'oauth_signature_method'=> 'HMAC-SHA1',
			'oauth_version'		=> '1.0',
        		'oauth_callback' 	=> REDIRECT_URI_SCRIPT."?".SOCIAL_SELECTOR."=TW",

			'link'		=> 'https://api.twitter.com/oauth/request_token',
			'access' 	=> 'https://api.twitter.com/oauth/access_token',
			),


	'user'		=> array (
			'oauth_callback'	=> REDIRECT_URI_SCRIPT."?".SOCIAL_SELECTOR."=TW",
			'link' 	=> 'https://api.twitter.com/1.1/users/show.json',
			),

	'enable'	=> 1
	);

//..............................................................................
// для сети "google+"
//..............................................................................
$soc_net['GG'] = array
	(
	'title'		=> 'Google+',
	'login'		=> 'google',
	'button'	=> array (
			'link'	=> 'https://accounts.google.com/o/oauth2/auth',
			'title'	=> 'OPENLOGIN_GG_TITLE',
			'class'	=> 'google',
			),

	'token' 	=> array (
			'param'	=> array (
			        'client_id' 	=> '631891840889-3ltoi9b3q3nae7a7d0rtla3hmunvlhm4.apps.googleusercontent.com',
				'response_type'	=> 'code',
				'scope' 	=> 'email profile',
	        		'redirect_uri' 	=> REDIRECT_URI_SCRIPT."?".SOCIAL_SELECTOR."=GG",
				),
			'link'	=> 'https://accounts.google.com/o/oauth2/token',
			),

	'user'		=> array (
	        	'client_secret' => '_NGqmcX8uPcYBCMjPTVLcQ0Q',
			'link'	=> 'https://www.googleapis.com/oauth2/v1/userinfo',
			),

	'post'		=> array (
			'link'	=> '',
			),

	'enable'	=> 1,
	);

//..............................................................................
// для сети "instagram"
//..............................................................................
$soc_net['IG'] = array
	(
	'title'		=> 'Instagram',
	'login'		=> 'instagram',
	'button'	=> array (
			'link'	=> 'https://api.instagram.com/oauth/authorize',
			'title'	=> 'OPENLOGIN_IG_TITLE',
			'class'	=> 'instagram',
			),

	'token' 	=> array (
			'param'	=> array (
				'grant_type'	=> 'authorization_code',
			        'client_id' 	=> '91ba983953b240e8ad51129c6b51c25c',
		        	'client_secret' => 'f09f9d9ec57b4f6cb52d01526088d3c4',
				'response_type'	=> 'code',
	        		'redirect_uri' 	=> REDIRECT_URI_SCRIPT."?".SOCIAL_SELECTOR."=IG",
				),
			'link'	=> 'https://api.instagram.com/oauth/access_token',
			),

	'user'		=> array (
	        	'client_secret' => 'f09f9d9ec57b4f6cb52d01526088d3c4',
			'link'	=> NULL,
			),

	'post'		=> array (
			'link'	=> NULL,
			),

	'enable'	=> 1,
	);





?>