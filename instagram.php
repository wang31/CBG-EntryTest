<?php
	require_once('simpleInstagramSearch.php');
	
	$clientID = '02220c0e36c747499d8a18daff2e37cd';
	if(isset($_GET['lat'])&&isset($_GET['lng'])){
		$getfield = 'lat='.$_GET['lat'].'&lng='.$_GET['lng'].'&distance=5000';
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
				$html = '<a href="'.$single['link'].'">'.'<img name="link" src="'.$single['images']['low_resolution']['url'].'" height="35%" width="50%"/></a>';
				
				echo $html;
			}
		}
	}
	else{
		echo 'You cannot visit this page before selecting a location. Go to the google map page first.';
	}
	echo '<form method="link" action="map.html"><input type="submit" value="Go back to map" style="height:150px; width:250px; margin-left:auto;margin-right:auto"></form>';
?>