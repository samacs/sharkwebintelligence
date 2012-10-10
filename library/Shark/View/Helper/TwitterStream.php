<?php
// @codingStandardsIgnoreStart
class Shark_View_Helper_TwitterStream extends Zend_View_Helper_Abstract {

	public function twitterStream($handle, $count = 5) {
		try {
			$client = new Zend_Http_Client('http://api.twitter.com/statuses/user_timeline.json');
			$client->setParameterGet('screen_name', $handle);
			$client->setParameterGet('count', $count);
			$response = $client->request(Zend_Http_Client::GET);
			$data = Zend_Json::decode($response->getBody(), Zend_JSon::TYPE_OBJECT);
			$output = '<ul>';
			foreach ($data as $tweet) {
				$text = Shark_String::linkify($tweet->text);
				$text = Shark_String::hashtagify($text);
				$text = Shark_String::userify($text);
				$output .= '<li>';
				$output .= '<img src="' . $tweet->user->profile_image_url . '" alt="@' . $tweet->user->screen_name . '" />';
				$output .= $text;
				$output .= '</li>';
			}
			$output .= '</ul>';
			return $output;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
}
// @codingStandardsIgnoreEnd