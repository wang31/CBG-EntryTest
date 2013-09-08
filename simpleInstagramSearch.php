<?php
	class SimpleInstagramAPIExchange{
		private $clientID;
		private $getfield;
		
		public function __construct($id){
			if(!in_array('curl', get_loaded_extensions())){
				throw new Exception('You need to install cURL, see: http://curl.haxx.se/docs/install.html');
			}
			$this->clientID = $id;
		}
		
		public function setGetField($string){
			$this->getfield = $string;
			return $this;
		}
		
		public function performRequest($url, $return = true)
		{
			if (!is_bool($return))
			{
				throw new Exception('performRequest parameter must be true or false');
			}
			$options = array(
							 CURLOPT_URL => $url,
							 CURLOPT_RETURNTRANSFER => true
							 );
			/*$options[CURLOPT_URL] .= '?client_id=';
			$options[CURLOPT_URL] .= this->$clientID;
			$options[CURLOPT_URL] .= '&';
			$options[CURLOPT_URL] .= this->$getfield;*/
			$feed = curl_init();
			curl_setopt_array($feed, $options);
			$json = curl_exec($feed);
			curl_close($feed);
			if ($return) { return $json; }
		}
		
	}
?>