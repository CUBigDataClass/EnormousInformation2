<?php

$connection = new MongoClient();

$db = $connection->WhatsPoppin;

function insertTwitter($text, $id) {
	$collection = $db->Twitter
	$doc = array(
		"text" => $text,
		"id" => $id
	);
	$collection->insert( $doc );
}

function insertYelp($name, $id) {
        $collection = $db->Yelp
        $doc = array(
                "name" => $name,
                "id" => $id
        );
	$collection->insert( $doc );
}

function insertInstagram($Q) {
        $collection = $db->instagram
        $doc = array(
                "tag" => $Q
        );
	$collection->insert( $doc );
}

?>
