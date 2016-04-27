<?php


	if(isset($_GET['term'])){ $term = $_GET['term']; }else{ $term = 'boulder OR Boulder'; };

	session_start();
	require_once("../twitteroauth-master/twitteroauth/twitteroauth.php"); //Path to twitteroauth library
	 
	$twitteruser = "-----";
	$consumerkey = "-----";
	$consumersecret = "-----";
	$accesstoken = "-----";
	$accesstokensecret = "-----";
	 
	function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
	  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
	  return $connection;
	}
	 
	$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);

	if(isset($_GET['rate'])){ 

		$url = "https://api.twitter.com/1.1/application/rate_limit_status.json?resources=help,users,search,statuses";

		$response = $connection->get($url);

		echo '<hr><pre>' . var_export($response, true) . '</pre>';

	}else{

			$url = "https://api.twitter.com/1.1/users/search.json?q=".$term."&count=1";

			$response = $connection->get($url);

			for ($x = 0; $x < count($response); $x++) {

				echo $response[$x]->screen_name;

				if($x < count($response)-1){ echo ','; }
			}
	}

?>