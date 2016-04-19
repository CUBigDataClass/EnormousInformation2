<?php

function insertOutput($poppinlocale) {
        $connection = new MongoClient(); //initiate mongo
        $db = $connection->WhatsPoppin; //use WhatsPoppin
        $collection = $db->result;
	
        $collection->update(
		array("poppinlocale" => $poppinlocale),
		array('$inc' => array("hits" => 1)),
		array("dayofweek" => date("l"))
		array("multiple" => true)
		);
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

?>
