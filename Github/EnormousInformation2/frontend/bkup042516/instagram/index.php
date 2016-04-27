<?php

$ACCESS_TOKEN = "394276664.1677ed0.019089bd6185427cb1c52f5a06888e72";
$API_URL = "https://api.instagram.com/v1/";

$response = file_get_contents("https://api.instagram.com/v1/media/search?lat=40.0150&lng=105.2705&access_token=394276664.1677ed0.019089bd6185427cb1c52f5a06888e72");

if(isset($_GET['tag'])){ 
    $hashtag = $_GET['tag']; 
    $response = file_get_contents($API_URL."tags/".$hashtag."/media/recent?access_token=".$ACCESS_TOKEN );
}

$response = json_decode($response);

for ($x = 0; $x < count($response->data); $x++) {
    echo '<img src="'.$response->data[$x]->images->thumbnail->url.'"><br>'.$response->data[$x]->user->username.' - '.$response->data[$x]->caption->text.' - '.'<br><br>';
} 

echo '<hr><pre>' . var_export($response, true) . '</pre>';
?>

