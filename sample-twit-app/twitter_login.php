<?php

require("twitteroauth/twitteroauth.php");
session_start();

define(CONSUMER_KEY, "N6J9vXixI2XA9oTDghdong");
define(CONSUMER_SECRET, "jlaj372BsyrOQUwe93lwdGNQ8ozPBsCEzQCDuUbiw");

$twitteroauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET);
$request_token = $twitteroauth->getRequestToken("http://jlojic.hostoi.com/mywork/php/sample-twit-app/twitter_oauth.php");

$_SESSION["oauth_token"] = $request_token["oauth_token"];
$_SESSION["oauth_token_secret"] = $request_token["oauth_token_secret"];

//echo $SESSION["oauth_token"];
//die;
if($twitteroauth->http_code == 200) {
  $url = $twitteroauth->getAuthorizeURL($request_token["oauth_token"]);
  header("Location: $url");
}else {
  die("Unknmow error");
}

?>