<?php

session_start();

require("twitteroauth/twitteroauth.php");


define(CONSUMER_KEY, "N6J9vXixI2XA9oTDghdong");
define(CONSUMER_SECRET, "jlaj372BsyrOQUwe93lwdGNQ8ozPBsCEzQCDuUbiw");

if(!empty($_SESSION['username'])){
  $search_name = $_GET['search_name'];
  $twitteroauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION["oauth_token"], $_SESSION["oauth_secret"]);
  $followers_ids = $twitteroauth->get('followers/ids')->ids;
  $followers_details = $twitteroauth->get('users/lookup', array('user_id' => $followers_ids));
  
  $follower_found = false;
  foreach($followers_details as $follower_detail) {
     if($search_name == '' || strpos(strtolower($follower_detail->name), strtolower($search_name)) !== false) {
        echo "<div><a href=\"javascript: getUserTweets('$follower_detail->name', $follower_detail->id)\">{$follower_detail->name}</a></div>";
        $follower_found = true;
     }
  }
  
  if($follower_found === false)
    echo 'No followers found...';
}

?>