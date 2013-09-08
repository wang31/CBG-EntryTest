<?php
	date_default_timezone_set('America/Los_Angeles');
	class TweetParser{
		private $tweet_count = 0;
		private $tweet_list;
		private $tweet_array;
		
		
		public function autolink ($tweet) {
			require_once(dirname(__FILE__).'/lib/twitter-text-php/lib/Twitter/Autolink.php');
			
			$autolinked_tweet = Twitter_Autolink::create($tweet, false)
			->setNoFollow(false)->setExternal(false)->setTarget('')
			->setUsernameClass('')
			->setHashtagClass('')
			->setURLClass('')
			->addLinks();
			
			return $autolinked_tweet;
		}
		
		/**
		 * Parse an individual tweet
		 */
		private function parse_tweet ($tweet) {
			// Format tweet text
			$tweet_text_raw = $tweet['text'];
			$tweet_text = $this->autolink($tweet_text_raw);
			
			// Tweet date is in GMT. Convert to UNIX timestamp in the local time of the tweeter.
			$utc_offset = $tweet['user']['utc_offset'];
			$tweet_time = strtotime($tweet['created_at']) + $utc_offset;
			
			if (false){
				// Convert tweet timestamp into Twitter style date ("About 2 hours ago")
				$current_time = time();
				$time_diff = abs($current_time - $tweet_time);
				switch ($time_diff) {
					case ($time_diff < 60):
						$display_time = $time_diff . ' ' . $this->options['twitter_date_text'][0] . ' ' . $this->options['twitter_date_text'][4];
						break;
					case ($time_diff >= 60 && $time_diff < 3600):
						$min = floor($time_diff/60);
						$display_time = $min . ' ' . $this->options['twitter_date_text'][1] . ' ' . $this->options['twitter_date_text'][4];
						break;
					case ($time_diff >= 3600 && $time_diff < 86400):
						$hour = floor($time_diff/3600);
						$display_time = $this->options['twitter_date_text'][2] . ' ' . $hour . ' ' . $this->options['twitter_date_text'][3];
						if ($hour > 1){ $display_time .= 's'; }
						$display_time .= ' ' . $this->options['twitter_date_text'][4];
						break;
					default:
						$format = str_replace('%O', date('S', $tweet_time), $this->options['date_format']);
						$display_time = strftime($format, $tweet_time);
						break;
				}
			} else {
				$format = str_replace('%O', date('S', $tweet_time), '%I:%M %p %b %d%O');
				$display_time = strftime($format, $tweet_time);
			}
			
			$href = 'http://twitter.com/' . $tweet['user']['screen_name'] . '/status/' . $tweet['id_str'];
			return '<li>'.'<span class="profilephoto"><img name='.'"'.$tweet['user'].'photo'.'" src="'.$tweet['user']['profile_image_url'].'" width="60" height="60"/></span>'.'<span class="status">' .'&nbsp;&nbsp;&nbsp;&nbsp;'.$tweet_text . '</span><span class="meta"> ' .'&nbsp;&nbsp;&nbsp;&nbsp;'. '<a href="' . $href . '">' . $display_time . '</a>' . '</span>' . '</li>';
		}
		
		//parse a list of tweets
		public function parse_json($tweet){
			$data = json_decode($tweet, true);
			//var_dump($data);
			$html = '<ul id="twitter">';
			foreach($data["statuses"] as $tweet){
				$html .= $this->parse_tweet($tweet);
			}
			$html .= '</ul>';
			echo $html;
		}
	}
?>