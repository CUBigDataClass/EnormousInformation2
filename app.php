<?php

error_reporting(E_ALL);

function insertOutput($poppinlocale) {
        $connection = new MongoClient(); //initiate mongo
        $db = $connection->WhatsPoppin; //use WhatsPoppin
        $collection = $db->result;
	
        $collection->update(
		array("poppinlocale" => $poppinlocale),
		array('$inc' => array("hits" => 1)),
		array("dayofweek" => date("l")),
		array("multiple" => true)
		);

		$cursor = $collection->find();

		foreach ($cursor as $document) {
		    echo $document["poppinlocale"] . "\n";
		}
}


function insertInput($text, $poppinlocale) {
	$connection = new MongoClient(); //initiate mongo
	$db = $connection->WhatsPoppin; //use WhatsPoppin
	$collection = $db->introdata;
	$doc = array(
		"message" => $text,
		"id" => $poppinlocale,
		"dayofweek" => date("l")
	);
	$collection->insert( $doc );
	insertOutput($poppinlocale);
}


insertOutput('TestPlace1');

?>