
<?php session_start(); 
require("twitteroauth/twitteroauth.php");


define(CONSUMER_KEY, "N6J9vXixI2XA9oTDghdong");
define(CONSUMER_SECRET, "jlaj372BsyrOQUwe93lwdGNQ8ozPBsCEzQCDuUbiw");
?>
<html>
<head>
   <script type="text/javascript" src="http://code.jquery.com/jquery-latest.min.js"></script>
   <script type="text/javascript" src="js/bjqs-1.3.min.js"></script>
   <link href="css/bjqs.css" type="text/css" rel="stylesheet"/>
   <script type="text/javascript">
      function getUserTweets(name, id) {
        //alert(id);
        $('#ajax-loader').removeClass('visibility-hidden');
        $.ajax({
             url: 'twitter_user_tweets.php?user_id=' + id,
             success: function(data) {
                         if(data == "0 Tweets") {
                            
                         }else {
                            $('#user-name').html(name + ' tweets...');
                            $('#slides').html(data);
                            href = '<a href="twitter_download_tweets.php?user_id=' + id + '" target="blank">Download as PDF</a>';
                            $('#download-tweet').html(href);
                            $('#slides').bjqs({width: 500, height: 100, animtype:'slide'});
                            $('#ajax-loader').addClass('visibility-hidden');
                         }
                      }
        });
      }
      $(document).ready(function() {
         $('#slides').bjqs({width: 500, height: 100, animtype:'fade'});
         
         $('#follower-name').keyup(function() {
             search_name = $(this).val();
             $('#ajax-loader').removeClass('visibility-hidden');
             $.ajax({
                    url: 'twitter_followers.php?search_name=' + search_name,
                    success: function(data) {
                        $('#followers-list').html(data);
                        $('#ajax-loader').addClass('visibility-hidden');
                    }
             });
         });
      });
   </script>
   <style type="text/css" media="screen">
            *{
				margin:0;
				padding:0;
			}
			body
			{
				padding: 20px;
			}
            #slides {
                width:500px;
                border: 2px solid #000000;
                background-color: #000000;
                margin:auto;
                padding: 0 50px 0 50px;
                color: red;
            }
            a{
              padding: 5px;
              color: green;
              text-decoration: none;
            }
            a:hover{
               text-decoration: underline;
            }
            #followers
            {
              width:500px;
              margin:auto;
              text-align: center;
              border: 0px solid red;
            }
            #followers-list
            {
              text-align: left;
            }
            .visibility-hidden
            {
              visibility: hidden;
            }
			.clear
			{
				clear: both;
			}
        </style>
</head>
<body>
<?php

if(!empty($_SESSION['username'])) {
  echo '<h2>Hello '."<a href='twitter_update.php'>@".$_SESSION['username'].'</a>';
  echo  '<span id="ajax-loader" class="visibility-hidden"><img src="images/ajax-loader.gif" width="25" height="25"/></span>';
  echo  '</h2>';
}else {
  echo '<h2>Hello Guest</h2>';
}

?>

<?php

if(!empty($_SESSION['username'])){

  $twitteroauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION["oauth_token"], $_SESSION["oauth_secret"]);
  $home_timeline = $twitteroauth->get('statuses/home_timeline', array('count' => 10));
  if(count($home_timeline) == 0) {
     echo 'No tweets found';
  }else{
     $uid = $_SESSION['oauth_uid'];
     echo '<div style="width:600px; margin:auto">';
     echo '   <div id="user-name" style="float:left">Your tweets...</div>';
     echo "   <div style='float:right' id='download-tweet'><a href='twitter_download_tweets.php?user_id=$uid' target='blank'>Download as PDF</a></div><div style='clear:both'></div>";
     echo '   <div id="slides"><ul class="bjqs">';
     foreach($home_timeline as $tweet) {
        echo '<li><p>'.$tweet->text.'</p></li>';
     }
     echo '</ul></div></div><br/><br/><br/><br/>';
  
     $followers_ids = $twitteroauth->get('followers/ids')->ids;
     $followers_details = $twitteroauth->get('users/lookup', array('user_id' => $followers_ids));
     if(count($followers_details) > 0) {
         echo '<div id="followers">';
         echo '<h3>Your followers....</h3>';
         echo '<input type="text" id="follower-name"/>';
         echo '<input type="button" value="search"/>';
         echo '<div id="followers-list">';
         foreach($followers_details as $follower_detail) {
            echo "<div><a href=\"javascript: getUserTweets('$follower_detail->name', $follower_detail->id)\">{$follower_detail->name}</a></div>";
         }
         echo '</div></div>';
     }else {
        echo 'No followers found...';
     }
  }
}
?>
</body>
</html>