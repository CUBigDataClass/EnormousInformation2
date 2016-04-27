<?php
	if(isset($_GET['term'])){ $term = $_GET['term']; }else{ $term = 'boulder OR Boulder'; };

	session_start();
	require_once("twitteroauth-master/twitteroauth/twitteroauth.php"); //Path to twitteroauth library
	 
	$twitteruser = "-----";
	$consumerkey = "-----";
	$consumersecret = "-----";
	$accesstoken = "------";
	$accesstokensecret = "-----";
	 
	function getConnectionWithAccessToken($cons_key, $cons_secret, $oauth_token, $oauth_token_secret) {
	  $connection = new TwitterOAuth($cons_key, $cons_secret, $oauth_token, $oauth_token_secret);
	  return $connection;
	}
	 
	$connection = getConnectionWithAccessToken($consumerkey, $consumersecret, $accesstoken, $accesstokensecret);

	$url = "https://api.twitter.com/1.1/search/tweets.json?q=".$term." since:2011-01-01&count=100";

	$response = $connection->get($url);

	for ($x = 0; $x < count($response->statuses); $x++) {

		$hashtags = '';

		for ($y = 0; $y < count($response->statuses[$x]->entities->hashtags); $y++) {
			$hashtags .= $response->statuses[$x]->entities->hashtags[$y]->text.",";
		}

		//for ($z = 0; $z < count($response->statuses[$z]->created_at); $z++) {

			$created_at = $response->statuses[$x]->created_at;

			if (strpos($created_at, 'Sun') !== False) { $created_at = 0; }

			
			else if (strpos($created_at, 'Mon') !== False){
			$created_at = 1;
                        $created_at .= $response->statuses[$z]->created_at[$z]",";
                        }
			else if (strpos($created_at, 'Tue') !== False){
                        $created_at = 2;
                        $created_at .= $response->statuses[$z]->created_at[$z]",";
                        }
			else if (strpos($created_at, 'Wed') !== False){
                        $created_at = 3;
                        $created_at .= $response->statuses[$z]->created_at[$z]",";
                        }
			else if (strpos($created_at, 'Thu') !== False){
                        $created_at = 4;
                        $created_at .= $response->statuses[$z]->created_at[$z]",";
                        }
			else if (strpos($created_at, 'Fri') !== False){
                        $created_at = 5;
                        $created_at .= $response->statuses[$z]->created_at[$z]",";
                        }
			else if (strpos($created_at, 'Sat') !== False){
                        $created_at = 6;
                        $created_at .= $response->statuses[$z]->created_at[$z]",";
                        }
		}

	    echo $response->statuses[$x]->user->screen_name.' - ' .$response->statuses[$x]->created_at[$x].' - '.$response->statuses[$x]->text.' - <b>'.$hashtags.'</b><br><br>';
	} 
	 
	echo '<hr><pre>' . var_export($response, true) . '</pre>';

?>



<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Twitter Calls</title>
</head>

<body>


</body>

</html>
