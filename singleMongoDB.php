<?php

function insertDatabase($text, $poppinlocale) {
	$connection = new MongoClient(); //initiate mongo
	$db = $connection->WhatsPoppin; //use WhatsPoppin

	$collection = $db->introdata;
	$doc = array(
		"text" => $text,
		"id" => $poppinlocale
	);
	$collection->insert( $doc );
}

function insertResult($name, $count, $text) {
        $connection = new MongoClient(); //initiate mongo
        $db = $connection->WhatsPoppin; //use WhatsPoppin

	$collection = $db->result;
        $doc = array(
                "name" => $name
		"count" => $count,
		"text" => $text
        );
        $collection->insert( $doc );
}

?>
