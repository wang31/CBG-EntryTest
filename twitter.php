<?php
	require_once('twitter-api-php-master/TwitterAPIExchange.php');
	$settings = array(
					  'oauth_access_token' => "1739483617-ceXbItS8CwlQJsBQljQxpp3cRcbo6g5w3yHLDRE",
					  'oauth_access_token_secret' => "GGkXLDhBfl5KJnlpjy8iOs3N2R5oz8IiJH5rfsng",
					  'consumer_key' => "yg01qxPIcNgxDBcXFlTyaQ",
					  'consumer_secret' => "kp0QCK024ZvHVulDArIJj69X0IekDRFcQP2gctKQQn0"
	);
	
	$url = 'https://api.twitter.com/1.1/search/tweets.json';
	if(isset($_GET['lat'])&&isset($_GET['lng'])){
		$getfield = '?q=%23usc&geocode='.$_GET['lat'].','.$_GET['lng'].',10mi&count=40';
		$requestMethod = 'GET';
		
		$twitter = new TwitterAPIExchange($settings);
		$data = $twitter->setGetfield($getfield)
		->buildOauth($url, $requestMethod)
		->performRequest(true);
		require_once('tweetparser.php');
		$parser = new TweetParser();
		$parser->parse_json($data);
	}
	else{
		echo 'You cannot visit this page before selecting a location. Go to the google map page first.';
	}
	echo '<form method="link" action="map.html"><input type="submit" value="Go back to map"></form>';
?>