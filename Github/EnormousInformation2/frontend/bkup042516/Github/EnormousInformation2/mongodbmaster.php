<?php

$connection = new MongoClient();

$db = $connection->WhatsPoppin;

function insertTwitter($text, $id) {
	$collection = $db->twitter
	$doc = array(
		"text" => $text,
		"id" => $id
	);
	$collection->insert( $doc );
}

function insertYelp($name, $id) {
        $collection = $db->yelp
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

function insertResult($name, $count, $text) {
        $collection = $db->result
        $doc = array(
                "name" => $name
		"count" => $count,
		"text" => $text
        );
        $collection->insert( $doc );
}

?>
