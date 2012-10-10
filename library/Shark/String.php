<?php
// @codingStandardsIgnoreStart
class Shark_String {

	public static function linkify($text)
	{
		return preg_replace('/(http?:\/\/\S+)/', '<a href="\1" target="_blank">\1</a>', $text);
	}

	public static function hashtagify($text)
	{
		return preg_replace('/(^|\s)#(\w+)/', '\1#<a href="http://search.twitter.com/search?q=%23\2" target="_blank">\2</a>', $text);
	}

	public static function userify($text)
	{
		return preg_replace('/(^|\s)@(\w+)/', '\1@<a href="http://twitter.com/\2" target="_blank">\2</a>', $text);
	}
}
// @codingStandardsIgnoreEnd