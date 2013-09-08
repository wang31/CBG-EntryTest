<?php
	require_once('simpleInstagramSearch.php');
	
	$clientID = '02220c0e36c747499d8a18daff2e37cd';
	$getfield = 'lat=34.0619&lng=-118.3024&distance=5000';
	$url = 'https://api.instagram.com/v1/media/search';
	
	$instagram = new SimpleInstagramAPIExchange($clientID);
	echo $instagram->setGetField($getfield)->performRequest($url, true);
?>