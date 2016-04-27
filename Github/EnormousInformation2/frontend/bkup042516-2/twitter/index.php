<?php
	if(isset($_GET['term'])){ $term = $_GET['term']; }else{ $term = 'boulder OR Boulder'; };

	session_start();
	require_once("twitteroauth-master/twitteroauth/twitteroauth.php"); //Path to twitteroauth library
	 
	$twitteruser = "Drodarious";
	$consumerkey = "MUYhC9XZA4GNQlvCyrKI1cSSI";
	$consumersecret = "SFCQWQKXgsGiZUepNR1TWkWQhDyvVHtjajt0uF3dhYc5SMTwzi";
	$accesstoken = "155705808-RS05lF0btfDuZbPZcZ5895igmn6CQu4LqmShSpR9";
	$accesstokensecret = "XTqWbRkStIn8S3GGfpsOts02sOGj9zsa7xMAKDQ8bGnc1";
	 
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

		$url = "https://api.twitter.com/1.1/search/tweets.json?q=".$term." since:2011-01-01&count=100";

		$response = $connection->get($url);

		$output = '{"tweets":[';

		for ($x = 0; $x < count($response->statuses); $x++) {

			$id = $response->statuses[$x]->id_str;
			$name = $response->statuses[$x]->user->screen_name;
			$text = str_replace('"', "", $response->statuses[$x]->text);
			$text = str_replace(",", "", $text);
			$created = $response->statuses[$x]->created_at;

			if (strpos($created, 'Sun') !== False){$created = 0;}
			if (strpos($created, 'Mon') !== False){$created = 1;}
			if (strpos($created, 'Tue') !== False){$created = 2;}
			if (strpos($created, 'Wed') !== False){$created = 3;}
			if (strpos($created, 'Thu') !== False){$created = 4;}
			if (strpos($created, 'Fri') !== False){$created = 5;}
			if (strpos($created, 'Sat') !== False){$created = 6;}

			$output .= '{"tweetId":"'.$id.'","name":"'.$name.'","text":"'.$text.'","created":"'.$created.'"}';

			if($x < count($response->statuses)-1){ $output .= ','; }
		} 

		$output .= ']}';

		echo $output;
		 
		//echo '<hr><pre>' . var_export($response, true) . '</pre>';
	}

?>
