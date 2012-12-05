<?php


session_start();

require("twitteroauth/twitteroauth.php");
require('pdf/fpdf.php');


define(CONSUMER_KEY, "N6J9vXixI2XA9oTDghdong");
define(CONSUMER_SECRET, "jlaj372BsyrOQUwe93lwdGNQ8ozPBsCEzQCDuUbiw");

if(!empty($_SESSION['username'])){
  $user_id = $_GET['user_id'];
  $twitteroauth = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $_SESSION["oauth_token"], $_SESSION["oauth_secret"]);
  $user_timeline = $twitteroauth->get('statuses/user_timeline', array('user_id' => $user_id, 'count' => 10));;


  $pdf = new FPDF();
  $pdf->AddPage();
  $pdf->SetFont('Arial','B',10);
  $y = 5;
  foreach($user_timeline as $tweet) {
     $pdf->MultiCell(0,$y, $tweet->text,0 ,1);
     $pdf->Ln();
  }
  $pdf->Output('Tweets'.$user_id.'.pdf', 'D');
}

?>