<?php

require_once('OAuth.php');

$consumer_key = 'anonymous';
$consumer_secret = 'anonymous';
$oauth_key = 'test_client';
$oauth_secret = 'test_secret';
$app_id = "stashboard";
$server_url="http://hha-api.slade360.co.ke";
$auth_url="/oauth2/token/";
$screening_url="/screening/staging/";
$treatment_url="/treatment/staging/";


$consumer = new OAuthConsumer($consumer_key, $consumer_secret);
$token = new OAuthToken($oauth_key, $oauth_secret);

// Set up a request function
function request($consumer, $token, $url, $method = "GET", $data = null) {

$sign = new OAuthSignatureMethod_HMAC_SHA1();
$request = OAuthRequest::from_consumer_and_token(
$consumer, $token, $method, $url, $data
);

$request->sign_request($sign, $consumer, $token);
$ch = curl_init($request->get_normalized_http_url());

if ($method == "POST") {
curl_setopt($ch, CURLOPT_POST ,1);
curl_setopt($ch, CURLOPT_POSTFIELDS , $request->to_postdata());
}

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION ,1);
curl_setopt($ch, CURLOPT_HEADER ,0);  // DO NOT RETURN HTTP HEADERS

return curl_exec($ch);
}

// Fill in your website
$base_url = "https://$app_id.appspot.com/admin/api/v1";

$data = array(
"name" => "An Example Service",
"description" => "An example service, created "
. "using the StashBoard API",
);

$r = request($consumer, $token, $base_url . "/services", "POST", $data);
$service = json_decode($r);

// GET the list of possible status images
$r = request($consumer, $token, $base_url . "/status-images", "GET");
$data = json_decode($r);
$images = $data->images;

// Pick a the first image
$image = $images[0];

// POST to the Statuses Resources to create a new Status
$data = array(
"name" => "Example Status",
"description" => "An example status, means nothing",
"level" => "NORMAL",
"image" => $image->name,
);

$r = request($consumer, $token, $base_url . "/statuses", "POST", $data);
$status = json_decode($r);

// Create a new event with the given status and given service
$data = array(
"message" => "Our first event! So exciting",
"status" => $status->id,
);

$r = request($consumer, $token, $service->url . "/events", "POST", $data);
$event = json_decode($r);

print_r($event);