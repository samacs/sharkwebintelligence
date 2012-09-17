<?php

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
				$output .= '<li>' . Shark_String::linkify($tweet->text) . '</li>';
			}
			$output .= '</ul>';
			return $output;
		} catch (Exception $e) {
			return $e->getMessage();
		}
	}
}