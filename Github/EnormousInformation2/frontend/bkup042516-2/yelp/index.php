<?php
/**
 * Yelp API v2.0 code sample.
 *
 * This program demonstrates the capability of the Yelp API version 2.0
 * by using the Search API to query for businesses by a search term and location,
 * and the Business API to query additional information about the top result
 * from the search query.
 * 
 * Please refer to http://www.yelp.com/developers/documentation for the API documentation.
 * 
 * This program requires a PHP OAuth2 library, which is included in this branch and can be
 * found here:
 *      http://oauth.googlecode.com/svn/code/php/
 * 
 * Sample usage of the program:
 * `php sample.php --term="bars" --location="San Francisco, CA"`
 */
// Enter the path that the oauth library is in relation to the php file
require_once('oauth.php');
// Set your OAuth credentials here  
// These credentials can be obtained from the 'Manage API Access' page in the
// developers documentation (http://www.yelp.com/developers)
$CONSUMER_KEY = 'OyE2yPvPuxbijW0dpU5jbQ';
$CONSUMER_SECRET = '6lbwm2K1hNNRsISdTQ9DGjBDPug';
$TOKEN = 'ymR-XEXeUIB3EKWE7uR4Ef2JI_iBmXF7';
$TOKEN_SECRET = '3cAJM0on_MJjU_p88VdyIPMjM6E';

$API_HOST = 'api.yelp.com';
if(isset($_GET['term'])){ $DEFAULT_TERM = $_GET['term']; }else{ $DEFAULT_TERM = 'nightlife'; };
$DEFAULT_LOCATION = 'Boulder, CO';
if(isset($_GET['limit'])){ $SEARCH_LIMIT = $_GET['limit']; }else{ $SEARCH_LIMIT = 8; };
$SEARCH_PATH = '/v2/search/';
$BUSINESS_PATH = '/v2/business/';
/** 
 * Makes a request to the Yelp API and returns the response
 * 
 * @param    $host    The domain host of the API 
 * @param    $path    The path of the APi after the domain
 * @return   The JSON response from the request      
 */
function request($host, $path) {
    $unsigned_url = "https://" . $host . $path;
    // Token object built using the OAuth library
    $token = new OAuthToken($GLOBALS['TOKEN'], $GLOBALS['TOKEN_SECRET']);
    // Consumer object built using the OAuth library
    $consumer = new OAuthConsumer($GLOBALS['CONSUMER_KEY'], $GLOBALS['CONSUMER_SECRET']);
    // Yelp uses HMAC SHA1 encoding
    $signature_method = new OAuthSignatureMethod_HMAC_SHA1();
    $oauthrequest = OAuthRequest::from_consumer_and_token(
        $consumer, 
        $token, 
        'GET', 
        $unsigned_url
    );
    
    // Sign the request
    $oauthrequest->sign_request($signature_method, $consumer, $token);
    
    // Get the signed URL
    $signed_url = $oauthrequest->to_url();
    
    // Send Yelp API Call
    try {
        $ch = curl_init($signed_url);
        if (FALSE === $ch)
            throw new Exception('Failed to initialize');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        $data = curl_exec($ch);
        if (FALSE === $data)
            throw new Exception(curl_error($ch), curl_errno($ch));
        $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (200 != $http_status)
            throw new Exception($data, $http_status);
        curl_close($ch);
    } catch(Exception $e) {
        trigger_error(sprintf(
            'Curl failed with error #%d: %s',
            $e->getCode(), $e->getMessage()),
            E_USER_ERROR);
    }
    
    return $data;
}
/**
 * Query the Search API by a search term and location 
 * 
 * @param    $term        The search term passed to the API 
 * @param    $location    The search location passed to the API 
 * @return   The JSON response from the request 
 */
function search($term, $location) {
    $url_params = array();
    
    $url_params['term'] = $term ?: $GLOBALS['DEFAULT_TERM'];
    $url_params['location'] = $location ?: $GLOBALS['DEFAULT_LOCATION'];
    $url_params['limit'] = $GLOBALS['SEARCH_LIMIT'];
    $url_params['sort'] = '2';
    $search_path = $GLOBALS['SEARCH_PATH'] . "?" . http_build_query($url_params);
    
    return request($GLOBALS['API_HOST'], $search_path);
}
/**
 * Query the Business API by business_id
 * 
 * @param    $business_id    The ID of the business to query
 * @return   The JSON response from the request 
 */
function get_business($business_id) {
    $business_path = $GLOBALS['BUSINESS_PATH'] . urlencode($business_id);
    
    return request($GLOBALS['API_HOST'], $business_path);
}
/**
 * Queries the API by the input values from the user 
 * 
 * @param    $term        The search term to query
 * @param    $location    The location of the business to query
 */

function query_api($term, $location) {  

	$response = search($term, $location);
	$response = json_decode($response);

	for ($x = 0; $x < count($response->businesses); $x++) {

        $loc = explode(",", strtolower($location));

        if($loc[0] == "collins"){ $loc[0] = "collins"; }

        $name = filterWords(strtolower($response->businesses[$x]->name));

        $name = explode(" ", $name);

        $twitterId = file_get_contents(urlencode('http://ec2-52-36-73-50.us-west-2.compute.amazonaws.com/twitter/users/?term='.$name[0].'%20'.$name[1].'%20'.$name[2].'%20'.$loc[0]));

        if($twitterId == ""){ $twitterId = file_get_contents('http://ec2-52-36-73-50.us-west-2.compute.amazonaws.com/twitter/users/?term='.$name[0].'%20'.$name[1].'%20'.$name[2]); }

        //$output .= 'http://ec2-52-36-73-50.us-west-2.compute.amazonaws.com/twitter/users/?term='.$name[0].'%20'.$name[1].'%20'.$name[2].'%20'.$loc[0].'<br>';

	    $output .= '{"name":"'.$response->businesses[$x]->name.'","url":"'.$response->businesses[$x]->url.'","city":"'.$response->businesses[$x]->location->city.'","term":"'.$term.'","twitterId":"'.$twitterId.'"}';
        if($x < count($response->businesses)-1){ $output .= ','; }
	} 

	//echo '<hr><pre>' . var_export($response, true) . '</pre>';

    return $output;

}

function buildJSON(){
    $output = '{';

    $output .= '"Boulder":[';
    $output .= query_api('restaurants', 'Boulder, CO').',';
    $output .= query_api('active', 'Boulder, CO').',';
    $output .= query_api('nightlife', 'Boulder, CO').',';
    $output .= query_api('arts', 'Boulder, CO').'],';

    $output .= '"Fort Collins":[';
    $output .= query_api('restaurants', 'Fort Collins, CO').',';
    $output .= query_api('active', 'Fort Collins, CO').',';
    $output .= query_api('nightlife', 'Fort Collins, CO').',';
    $output .= query_api('arts', 'Fort Collins, CO').'],';

    $output .= '"Denver":[';
    $output .= query_api('restaurants', 'Denver, CO').',';
    $output .= query_api('active', 'Denver, CO').',';
    $output .= query_api('nightlife', 'Denver, CO').',';
    $output .= query_api('arts', 'Denver, CO').']';

    $output .= '}';

    echo $output;
}

function filterWords($text){
    $filterWords = array('brewery','company','brewing');

    $text = str_replace("&", "and", $text);

    for ($x = 0; $x < count($filterWords); $x++) {
        $text = str_replace($filterWords[$x], "", $text);
    }

    return $text;
}

buildJSON();

/**
 * User input is handled here 
 */
$longopts = array(
    "term::",
    "location::",
);
    
$options = getopt("", $longopts);
$term = $options['term'] ?: '';
$location = $options['location'] ?: '';

?>