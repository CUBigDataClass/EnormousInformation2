<?php

error_reporting(E_ALL);

// connect
$m = new MongoClient();
// select (w)hats(p)oppin database
$db = $m->wp;
// select a collection (table)
$collection = $db->locations;

//$collection->drop();

function addNewLocations($collection){
	$locationsStr = file_get_contents('http://ec2-52-36-73-50.us-west-2.compute.amazonaws.com/yelp/test.txt'); // get json string of locations

	$locationsObj = json_decode($locationsStr); // convert string to object

	foreach ($locationsObj as $location) { // for each parent item in the json object...
		for ($i=0; $i < count($location); $i++) { // ...parse through each entity

			$collection->update( // update table
				array(  "name" => $location[$i]->name), //look for previous entries with same name
				array(  "name" => $location[$i]->name,  // update with this info
						"url" => $location[$i]->url,
						"city" => $location[$i]->city,
						"term" => $location[$i]->term,
						"twitter" => $location[$i]->twitter),
				array(  "upsert" => true) // "upsert", if the entry doesn't exist, make a new entry
			);
		}
	}
}
//addNewLocations($collection);


function updateTweets($collection){

	$cursor = $collection->find(); // get all table entries...

	foreach ($cursor as $document) { // ...and for each one...

		$locationName = $document["name"];

		if($document["twitter"]){ // ... check if it has an associated twitter account.
			$twitter = $document["twitter"];
			$tweetsStr = file_get_contents('http://ec2-52-36-73-50.us-west-2.compute.amazonaws.com/twitter/?term='.$twitter); // If it does, get json string of tweets about that account.
		}else{
			$name = str_replace(" ", "%20", strtolower($document["name"]));
			$tweetsStr = file_get_contents('http://ec2-52-36-73-50.us-west-2.compute.amazonaws.com/twitter/?term='.$name); // If it doesn't, get json string of tweets via location name.
		}

		$tweetsObj = json_decode($tweetsStr); // Convert string to object.


		foreach ($tweetsObj as $tweet) { // for each parent item in the json object...

			for ($i=0; $i < count($tweet); $i++) { // ...parse through each entity

				if($tweet[$i]->created == 0 ){ $collection->update(array("name" => $locationName), array('$addToSet' => array("sunday" => $tweet[$i]->tweetId.'||'.$tweet[$i]->name.'||'.$tweet[$i]->text.'||'.$tweet[$i]->created)));}
				if($tweet[$i]->created == 1 ){ $collection->update(array("name" => $locationName), array('$addToSet' => array("monday" => $tweet[$i]->tweetId.'||'.$tweet[$i]->name.'||'.$tweet[$i]->text.'||'.$tweet[$i]->created)));}
				if($tweet[$i]->created == 2 ){ $collection->update(array("name" => $locationName), array('$addToSet' => array("tuesday" => $tweet[$i]->tweetId.'||'.$tweet[$i]->name.'||'.$tweet[$i]->text.'||'.$tweet[$i]->created)));}
				if($tweet[$i]->created == 3 ){ $collection->update(array("name" => $locationName), array('$addToSet' => array("wednesday" => $tweet[$i]->tweetId.'||'.$tweet[$i]->name.'||'.$tweet[$i]->text.'||'.$tweet[$i]->created)));}
				if($tweet[$i]->created == 4 ){ $collection->update(array("name" => $locationName), array('$addToSet' => array("thursday" => $tweet[$i]->tweetId.'||'.$tweet[$i]->name.'||'.$tweet[$i]->text.'||'.$tweet[$i]->created)));}
				if($tweet[$i]->created == 5 ){ $collection->update(array("name" => $locationName), array('$addToSet' => array("friday" => $tweet[$i]->tweetId.'||'.$tweet[$i]->name.'||'.$tweet[$i]->text.'||'.$tweet[$i]->created)));}
				if($tweet[$i]->created == 6 ){ $collection->update(array("name" => $locationName), array('$addToSet' => array("saturday" => $tweet[$i]->tweetId.'||'.$tweet[$i]->name.'||'.$tweet[$i]->text.'||'.$tweet[$i]->created)));}
			}
		}
	}
}
//updateTweets($collection);


function showData($collection, $field){
	$cursor = $collection->find();
	foreach ($cursor as $document) {
		echo '<pre>'.var_dump($document[$field]).'<pre><hr>';
	}
}
//showData($collection, 'term');

if(isset($_GET['q']) && $_GET['q'] = 1){ 

	$city = $_GET['city']; 
	$term = $_GET['term'];

	$output .= '{';

	$cursor = $collection->find(array('city' => $city,'term' => $term));
	$x = 0;
	$length = $cursor->count();

	foreach ($cursor as $document) {
		$output .= '"'.$document['name'].'":"';
		$output .= count($document['sunday']).'|';
		$output .= count($document['monday']).'|';
		$output .= count($document['tuesday']).'|';
		$output .= count($document['wednesday']).'|';
		$output .= count($document['thursday']).'|';
		$output .= count($document['friday']).'|';
		$output .= count($document['saturday']).'"';

		if ($x < $length-1){ $output .= ','; }

		$x++;
	}
	$output .= '}';

	echo $output;
}
?>