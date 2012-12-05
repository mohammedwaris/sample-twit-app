<?php
session_start();

require("twitteroauth/twitteroauth.php");


define(CONSUMER_KEY, "N6J9vXixI2XA9oTDghdong");
define(CONSUMER_SECRET, "jlaj372BsyrOQUwe93lwdGNQ8ozPBsCEzQCDuUbiw");

if(!empty($_SESSION['username'])){
  $user_id = $_GET['user_id'];
  $twitteroauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION["oauth_token"], $_SESSION["oauth_secret"]);
  $user_timeline = $twitteroauth->get('statuses/user_timeline', array('user_id' => $user_id, 'count' => 10));
  echo '<ul class="bjqs">';
  foreach($user_timeline as $tweet) {
     echo "<li><p>$tweet->text</p></li>";
  }
  //echo "<li><p>$tweet->text</p></li>";
  echo '</ul>';
}
?>