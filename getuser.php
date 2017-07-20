<?php

/* Load required lib files. */
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
include_once 'lib/twitteroauth.php';
include_once 'twConfig.php';

/* If access tokens are not available redirect to connect page. */
if (empty($_SESSION['access_token']) || empty($_SESSION['access_token']['oauth_token']) || empty($_SESSION['access_token']['oauth_token_secret'])) {
    header('Location: clearsessions.php');
}
/* Get user access tokens out of the session. */
$access_token = $_SESSION['access_token'];

/* Create a TwitterOauth object with consumer/user tokens. */
$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, $access_token['oauth_token'], $access_token['oauth_token_secret']);

/* If method is set change API call made. Test is called by default. */
$username = $_GET['name'];
$myTweets = $connection->get('statuses/user_timeline', array('screen_name' => $username, 'count' => 10));

if(empty($myTweets))
{
	$content2 = '<header class=" blueback text-center container-fluid">
  <h3>'.$username.'</strong>\'s TWEETS</h3>
</header><p>NO TWEETS TO DISPLAY</p>';
}
else
{
	if($username===$_SESSION['username'])
        {
                     $content2 = '<header class=" blueback text-center container-fluid">
                     <h3>MY TWEETS</strong></h3>
                     </header>';
         }
        else
        {
                     $content2 = '<header class=" blueback text-center container-fluid">
                     <h3>'.$username.'</strong>\'s TWEETS</h3>
                     </header>';
         }
        $content2 .= '<div id="myCarousel" class="carousel slide" data-ride="carousel" style="margin-top:32px!important;">';
	$content2 .= '<div class="carousel-inner">';
	$i = 1;
	foreach($myTweets as $tweet){
		if($i == 1)
		{
			$content2 .= '<div class="item active">
			<p>'.$tweet->text.'<br>'.$tweet->created_at.'</p>
		</div>';
		}
		else
		{
			$content2 .= '<div class="item">
			<p>'.$tweet->text.'<br>'.$tweet->created_at.'</p>
		</div>';
		}
		$i++;
	}
	$content2 .= '</div>

	<a class="left carousel-control" href="#myCarousel" data-slide="prev">
		<span class="glyphicon glyphicon-chevron-left"></span>
		<span class="sr-only">Previous</span>
	</a>
	<a class="right carousel-control" href="#myCarousel" data-slide="next">
		<span class="glyphicon glyphicon-chevron-right"></span>
		<span class="sr-only">Next</span>
	</a>
	</div>';
}
echo $content2;
?>