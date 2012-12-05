<?php

session_start();
require('twitteroauth/twitteroauth.php');

mysql_connect('mysql10.000webhost.com', 'a8696497_twitter', 'twitter123');
mysql_select_db('a8696497_twitter');

if(!empty($_SESSION["username"])) {
  header('Location: twitter_update.php');
}

define(CONSUMER_KEY, "N6J9vXixI2XA9oTDghdong");
define(CONSUMER_SECRET, "jlaj372BsyrOQUwe93lwdGNQ8ozPBsCEzQCDuUbiw");

if(!empty($_GET["oauth_verifier"]) && !empty($_SESSION["oauth_token"]) && !empty($_SESSION["oauth_token_secret"])) {

   $twitteroauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION["oauth_token"], $_SESSION["oauth_token_secret"]);
   $access_token = $twitteroauth->getAccessToken($_GET["oauth_verifier"]);
   
   $_SESSION["access_token"] = $access_token;
   $user_info = $twitteroauth->get('account/verify_credentials');
  
   if(isset($user_info->error)) {
      header('Location: twitter_login.php');
   }else {
      $sql = "SELECT * FROM users WHERE oauth_providor = 'twitter' AND oauth_uid = '". $user_info->id ."'";
      $query = mysql_query($sql);
      $result = mysql_fetch_array($query);

      if(empty($result)) {
         $query = mysql_query("INSERT INTO users(oauth_providor, oauth_uid, username, oauth_token, oauth_secret) VALUES ('twitter', '{$user_info->id}', '{$user_info->screen_name}', '{$access_token['oauth_token']}', '{$access_token['oauth_token_secret']}')");
         $query = mysql_query("SELECT * FROM users WHERE id = " . mysql_insert_id());
         $result = mysql_fetch_array($query);
      } else {
         $query = mysql_query("UPDATE users SET oauth_token = '{$access_token['oauth_token']}', oauth_secret = '{$access_token['oauth_token_secret']}' WHERE oauth_providor = 'twitter' AND oauth_uid = '{$user_info->id}'");
      }
      $_SESSION['id'] = $result['id'];
      $_SESSION['username'] = $result['username'];
      $_SESSION['oauth_uid'] = $result['oauth_uid'];
      $_SESSION['oauth_providor'] = $result['oauth_providor'];
      $_SESSION['oauth_token'] = $result['oauth_token'];
      $_SESSION['oauth_secret'] = $result['oauth_secret'];
      header('Location: twitter_update.php');
   }

}else {
  header("Location: twitter_login.php");
}

?>