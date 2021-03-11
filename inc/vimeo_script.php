<?php

echo "TEST THIS";

// include the autoload file from the vimeo php library that was downloaded
include '/var/www/html/functions/vimeo/autoload.php';

// The client id and client secret needed to use the vimeo API
$clientId = "7e7e85cfdae62ace9c329b6566768c164604d9e0";
$clientSecret = "DVTbe9I79nwg79nucCJYz0SF5Ladup0+5eXwdGQTVFBHGiw8j9OlH5gq1/TkMkXoWWNsGATFQJnzFY8/npQiGLp8qum+0J2akr1XNuJoR5whmZddwZ4vT9cOxSrkWqGq";

// when getting an auth token we need to provide the scope
// all possible scopes can be found here https://developer.vimeo.com/api/authentication#supported-scopes
$scope = "public";

// The id of the user
$userId = "user19288015";

// initialize the vimeo library
$lib = new \Vimeo\Vimeo($clientId, $clientSecret);

// request an auth token (needed for all requests to the Vimeo API)
$token = $lib->clientCredentials($scope);

// set the token1
$lib->setToken($token['body']['access_token']);

// request all of a user's videos, 50 per page
// a complete list of all endpoints can be found here https://developer.vimeo.com/api/endpoints
$videos = $lib->request("/users/$userId/albums/4340850/videos", ['per_page' => 99]);

//print_r($videos['body']['data']);
echo "<ol>"; 
// loop through each video from the user
foreach($videos['body']['data'] as $arr) {
	//print_r($arr);
    // get the link to the video
    $link = $arr['link']; echo "<li>".$link."</li>";

    // get the largest picture "thumb"
    $pictures = $arr['pictures']['sizes'];
    $largestPicture = $pictures[count($pictures) - 1]['link'];
}
echo "</ol>";
?>