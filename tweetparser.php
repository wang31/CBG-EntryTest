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
			
			if (true){
				// Convert tweet timestamp into Twitter style date ("About 2 hours ago")
				$current_time = time();
				$time_diff = abs($current_time - $tweet_time);
				switch ($time_diff) {
					case ($time_diff < 60):
						$display_time = $time_diff . ' ' . 'seconds' . ' ' . 'ago';
						break;
					case ($time_diff >= 60 && $time_diff < 3600):
						$min = floor($time_diff/60);
						$display_time = $min . ' ' . 'minutes' . ' ' . 'ago';
						break;
					case ($time_diff >= 3600 && $time_diff < 86400):
						$hour = floor($time_diff/3600);
						$display_time = 'about' . ' ' . $hour . ' ' . 'hour';
						if ($hour > 1){ $display_time .= 's'; }
						$display_time .= ' ' . 'ago';
						break;
					default:
						$format = str_replace('%O', date('S', $tweet_time), '%I:%M %p %b %d%O');
						$display_time = strftime($format, $tweet_time);
						break;
				}
			} else {
				$format = str_replace('%O', date('S', $tweet_time), '%I:%M %p %b %d%O');
				$display_time = strftime($format, $tweet_time);
			}
			
			$href = 'http://twitter.com/' . $tweet['user']['screen_name'] . '/status/' . $tweet['id_str'];
			$profilePage = 'http://twitter.com/' . $tweet['user']['screen_name'];
			//the profile photo
			$returnV = '<div name="cell" style="margin-bottom:40px"><li>'.'<span class="profilephoto"><a href="'.$profilePage.'">'.'<img name='.'"'.$tweet['user'].'photo'.'" src="'.$tweet['user']['profile_image_url'].'" width="120" height="120"/></a></span>';
			//the username
			$returnV .= '<span class="username" style="font-size:25px">'.'<a href="'.$profilePage.'">'.'@'.$tweet['user']['screen_name'].'</a>'.'</span>';
			//the content and time
			$returnV .= '<br><br><br><span class="status" style="font-size:30px">' .'&nbsp;&nbsp;&nbsp;&nbsp;'.$tweet_text . '</span><span class="meta" style="font-size:25px"> ' .'&nbsp;&nbsp;&nbsp;&nbsp;'. '<a href="' . $href . '">' . $display_time . '</a>' . '</span>' . '</li></div>';
			return $returnV;
		}
		
		//parse a list of tweets
		public function parse_json($tweet){
			$data = json_decode($tweet, true);
			//var_dump($data);
			$html = '<ul id="twitter">';
			foreach($data["statuses"] as $tweet){
				$html .= $this->parse_tweet($tweet);
				$html .= '<hr size="3">';
			}
			$html .= '</ul>';
			echo $html;
		}
	}
?>