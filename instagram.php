<?php
	require_once('simpleInstagramSearch.php');
	
	$clientID = '02220c0e36c747499d8a18daff2e37cd';
	$getfield = 'lat=34.0619&lng=-118.3024&distance=5000';
	$url = 'https://api.instagram.com/v1/media/search';
	
	$instagram = new SimpleInstagramAPIExchange($clientID);
	$json = $instagram->setGetField($getfield)->performRequest($url, true);
	$data = json_decode($json, true);
	//var_dump($data);
	if($data['meta']['code'] != 200){
		echo $data['meta']['error_message'];
	}
	else{
		foreach($data['data'] as $single){
			$html = '<div class="pic" style="height:80%;width:100%"><img name="link" src="'.$single['images']['low_resolution']['url'].'"/></div>';
			echo $html;
		}
		
	}
?>