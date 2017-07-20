<?php
/**
 * @file
 * User has successfully authenticated with Twitter. Access tokens saved to session and DB.
 */

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
$data = $connection->get('account/verify_credentials');
$username    = $data->screen_name;
$_SESSION['username']=$username;

$cursor=-1;
 	$users=array();
 	while($cursor != 0){
		$tempuser = $connection->get('followers/list', array('screen_name'=>$username,'cursor'=>$cursor,'count'=>200));
		$data2 = json_decode(json_encode($tempuser));
		$cursor = $data2->next_cursor_str;
		foreach ($data2->users as $user) {
			array_push($users, $user->screen_name);
		}
		}

$content2 = '<div class="row"><div class="col-md-3 card whiteback" style="margin-left:3%;margin-right:1%;margin-top:1%;margin-bottom:1%;padding-bottom:32px!important;min-height:50%"><header class=" blueback text-center container-fluid">
  <h3>Welcome <strong>'.$username.'</strong> <br>(Twitter ID : '.$data->id.')</h3>
</header>';
    $content2 .= '<img class="img-responsive center-block" src="'.$data->profile_image_url.'" width="50%" height="50%"  style="padding-top:32px!important;"/><br>
<button type="button" class="btn btn-default" id="download" data-toggle="modal" data-target="#myModal">DOWNLOAD TWEETS</button>
    <button type="button" class="btn btn-default" id="logout" onclick="reDirect()">LOGOUT</button>';
    $content2 .= '</div>';
    
    //Get latest tweets
    $myTweets = $connection->get('statuses/user_timeline', array('screen_name' => $username, 'count' => 10));
    $_SESSION['myTweets']=json_decode(json_encode($myTweets));

    $content2 .= '<div class="col-md-8 card whiteback" style="margin-top:1%;margin-bottom:1%;min-height:50%"><div class="row blueback container-fluid">
		<br/>			
			<div class="input-group">
				<input name="name" id="name" class="typeahead form-control" type="text">
				<span class="input-group-btn">
					<button type="button" class="btn btn-default" id="search" name="search" onclick="showUser(document.getElementById(\'name\').value)">SEARCH</button>
 <button type="button" class="btn btn-default" id="mytweets" onclick="showUser(\''.$username.'\')">MY TWEETS</button>
				</span>
			</div>
			</div>
<div class="row container-fluid blueback">
<br><div class="col-md-1"><strong>SOME USERS :-</strong></div>';
	for($i=0;$i<10;$i++)
	{
		$content2 .= '<div class="col-md-1"><a href ="#" onclick="showUser(\''.$users[$i].'\')" style="color:white">'.$users[$i].'</a></div>';
	}
	$content2	.= '</div><div class="col-md-12 whiteback">
	<div class="row">
		<br/>		<span id="loading"></span><div id="txtHint" style="min-height:10%">SEARCH USERS TO SEE TWEETS</div></div></div></div>
	';
    $content2 .= '</div></div></div>
       <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
    
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Select format</h4>
        </div>
        <div class="modal-body">
          <div class="btn-group">
  <button type="button" class="btn btn-primary" onclick="downloadCSV()">CSV</button>
  <button type="button" class="btn btn-primary" onclick="downloadJSON()">JSON</button>
  <button type="button" class="btn btn-primary" onclick="downloadXML()">XML</button>
</div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
      </div>
      
    </div>
  </div>';  
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

    <title>Login with Twitter using PHP</title>
<link rel="stylesheet" href="styles/style.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.1/bootstrap3-typeahead.min.js"></script>
<script>
function showUser(str) {

  if (str=="") {
    document.getElementById("txtHint").innerHTML="";
    return;
  } 
  if (window.XMLHttpRequest) {
    // code for IE7+, Firefox, Chrome, Opera, Safari
    xmlhttp=new XMLHttpRequest();
  } else { // code for IE6, IE5
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
  }
  xmlhttp.onreadystatechange=function() {
    if (this.readyState==4 && this.status==200) {
      document.getElementById("loading").innerHTML = '';
      document.getElementById("txtHint").innerHTML=this.responseText;
    }
  }
  document.getElementById("loading").innerHTML = '<img src="images/loader.gif" />';
  document.getElementById("txtHint").innerHTML = "";
  xmlhttp.open("GET","getuser.php?name="+str,true);
  xmlhttp.send();
}
</script>
<style>
body
{
background-color:#d2d2d2
}
</style>
</head>
<body>

<?php 
echo $content2;  ?>
<script>

	var users = <?php echo json_encode($users); ?>;

	$('input.typeahead').typeahead({
	    source:  users,
	    limit:10
	});
</script>
<script>
function reDirect()
		{
			window.location.href='clearsessions.php';
		}
function downloadCSV()
{
   window.location.href='downloadCSV.php';
}
function downloadJSON()
{
   window.location.href='downloadJSON.php';
}
function downloadXML()
{
   window.location.href='downloadXML.php';
}
</script>
<footer class="text-center">
  <p>TWITTER USER LOGIN</p> 
</footer>
</body>
</html>