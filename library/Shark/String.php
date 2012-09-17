<?php

class Shark_String {
	
	public static function linkify($text, $twitterUsernames = true, $hashTags = true) {
		// linkify URLs
		$result = preg_replace('/(https?:\/\/\S+)/', '<a href="\1">\1</a>', $text);

		// linkify Twitter user names
		if ($twitterUsernames) {
			$result = preg_replace('/(^|\s)@(\w+)/', '\1@<a href="http://twitter.com/\2">\2</a>', $text);
		}

		// linkify Twitter hash tags
		if ($hashTags) {
			$result = preg_replace('/(^|\s)#(\w+)/', '\1#<a href="http://search.twitter.com/search?q=%23\2">\2</a>', $text);
		}
		return $result;
	}
}