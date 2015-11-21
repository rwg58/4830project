<!DOCTYPE html>
<html lang="en">
<head>
  <title>Twitter Analysis</title>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
  <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>

<nav class="navbar navbar-default">
  <div class="container-fluid">
    <div class="navbar-header">
      <a class="navbar-brand" href="#">Hacky T</a>
    </div>
    <div>
      <ul class="nav navbar-nav">
        <li class="active"><a href="#">Home</a></li>
        <li><a href="#">Page 1</a></li>
 
      </ul>
    </div>
  </div>
</nav>
  
<div class="container">

<?php


$i=0;
$screenname =$_POST['screenname'];
$checkfirstline = 0;
//total of favourites


$favourite_tweet_count = 0;
 $favourite_account_count = 0;
$num_of_followers = 0;
$friends_count = 0;
$status_count = 0;

$max_favourite_tweet = '';
$max_retweet_text = '';
$pop_count = 0;
$max_favourite = 0;
$history_max_favourite = 100;

$max_retweet_tweet = '';
$max_retweet = 0;
$history_max_retweet = 100;
$history_friend_count = 100;
$history_favourite_account_count = 200;
$str = '';
do{
require_once('TwitterAPIExchange.php');
 
/** Set access tokens here - see: https://dev.twitter.com/apps/ **/
$settings = array(
    'oauth_access_token' => "3117084976-2CvxaDXZcjfwYrKDKI5rWCSj4tkDZ4qMIZ3Nu88",
    'oauth_access_token_secret' => "JkmYqqQo9MGVL1JCxfSmOq9O3mdLEYmInFqC8mVgHLGkh",
    'consumer_key' => "SwV1YIpn1BN5Xbl5tpsU5bUPj",
    'consumer_secret' => "rSZuWeOqX9nK38ETOwERHnwmwhRDh19GiWi6jDPxnYB3meKjT4"
);
$url = "https://api.twitter.com/1.1/statuses/user_timeline.json";
$requestMethod = "GET";
if($i==0){
$getfield = "?screen_name=$screenname&count=200";
echo "created_at,text<br>";
}else{
    $getfield = "?screen_name=$screenname&max_id=$max_id&count=200";

}
$twitter = new TwitterAPIExchange($settings);

//echo $twitter->setGetfield($getfield)
  //           ->buildOauth($url, $requestMethod)
   //          ->performRequest();


$string = json_decode($twitter->setGetfield($getfield)
->buildOauth($url, $requestMethod)
->performRequest(),$assoc = TRUE);
//success

		if($checkfirstline == 0){
	//		echo $line[$qq] . "," . $llll[$qq] . "," . $string[0]['user']['statuses_count'] ."," . $string[0]['user']['friends_count'] ."," . $string[0]['user']['followers_count'] . "," . $string[0]['user']['favourites_count'] . "," . $string[0]['user']['created_at'] . "<br>";
			$checkfirstline++;
 			$status_count = $string[0]['user']['statuses_count'];
			$friends_count = $string[0]['user']['friends_count'];
			$favourite_account_count = $string[0]['user']['favourites_count'];
 
		}
			
	//	print_r($string);	

for($c=0;$c<200;$c++){
	
	if(empty($string[$c]['in_reply_to_user_id']) && empty($string[$c]['in_reply_to_status_id']) ){
		
 
	//	echo $string[$c]['created_at'] ."," . $string[$c]['text']. "," . $string[$c]['retweet_count'] ."," . $string[$c]['favorite_count']  . "<br>";
		$favourite_tweet_count = $favourite_tweet_count + $string[$c]['favorite_count'] ;
		
		//get max_favourite tweet info
		if($string[$c]['favorite_count'] > $max_favourite){
			$max_favourite = $string[$c]['favorite_count'];
			$max_favourite_tweet = $string[$c]['text'];
		}
		
		if($string[$c]['retweet_count'] > $max_retweet){
			$max_retweet = $string[$c]['retweet_count'];
			$max_retweet_text = $string[$c]['text'];
		}
		if($string[$c]['retweet_count'] >= $history_max_retweet - 50 && $string[$c]['favorite_count'] >= $history_max_favourite - 50){
			$str = $str . $string[$c]['text'];
			echo "<p>" . $string[$c]['retweet_count'] . " <span class = 'glyphicon glyphicon-thumbs-up'>  </span> " . $string[$c]['text'] . "</span>";


		}
	}

}

		if(!empty($string[199]['id'])){
			$max_id = $string[199]['id'];
		}else if (!empty($string[198]['id'])){
			$max_id = $string[198]['id'];
		}else if (!empty($string[197]['id'])){
			$max_id = $string[197]['id'];
		}else {
			break;
		}
    
 $i++;
}while ($i<16);
//echo "<br><br><br><br>";
	 
	$pop_key_words = array_count_values(str_word_count($str,1));

//	echo "<br><br><br><br>";
	arsort($pop_key_words);
//	print_r($pop_key_words);

	$top10 = (array_slice($pop_key_words,0,15));
//	print_r($top10);
	
//	foreach ($top10 as $key => $val) {
//		echo "$key = $val\n";
//	}
?>

  <h1>Your Top Tweets Recently</h1>


 
 
	<div class = "well">
		<h4>Max favourite tweet</h4>
		<?php
			echo "<p>" . $max_favourite_tweet . "</p>";
		?>
		<h4>Max retweet tweet</h4>		<?php
			echo "<p>" . $max_retweet_text . "</p>";
		?>
	</div>
	<div class = "alert alert-success">
		<h3>Top Popular words</h3>
		<?php
				foreach ($top10 as $key => $val) {
					echo "   $key = $val   |";
				}
		?>
	</div>
	
	<div class="row">
  <div class="col-xs-6 col-md-3">
    <a href="#" class="thumbnail">
		<p>Tweets</p>
		<?php echo $status_count; ?>
    </a>
  </div>
   <div class="col-xs-6 col-md-3">
    <a href="#" class="thumbnail">
		<p>Favourites</p>
		<?php echo $friends_count; ?> <?php if($friends_count > $history_friend_count){
			echo "<span class = 'glyphicon glyphicon-hand-up' style = 'font-size:30px;'></span>  $history_friend_count";
		} else {
			echo "<span class = 'glyphicon glyphicon-hand-down' style = 'font-size:30px;'></span>  $history_friend_count";
		} ?>
    </a>
  </div>
   <div class="col-xs-6 col-md-3">
    <a href="#" class="thumbnail">
		<p>Followers</p>
		<?php echo $favourite_account_count; ?> <?php if($favourite_account_count > $history_favourite_account_count){
			echo "<span class = 'glyphicon glyphicon-hand-up' style = 'font-size:30px;'></span>  $history_favourite_account_count";
		} else {
			echo "<span class = 'glyphicon glyphicon-hand-down' style = 'font-size:30px;'></span>  $history_favourite_account_count";
		} ?>
    </a>
  </div>

  
 </div>
</div>

</body>
</html>

