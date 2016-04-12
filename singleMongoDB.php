<?php

$connection = new MongoClient();

$db = $connection->WhatsPoppin;

function insertDatabase($text, $poppinlocale) {
	$collection = $db->introdata
	$doc = array(
		"text" => $text,
		"id" => $poppinlocale
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
