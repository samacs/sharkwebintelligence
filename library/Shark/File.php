<?php

class Shark_File {
	
	public static function getSubDirectories($path) {
		if (!file_exists($path)) {
			return array();
		}
		$subDirectories = array();
		$iterator = new DirectoryIterator($path);
		foreach ($iterator as $directory) {
			if ($directory->isDot() || !$directory->isDir()) {
				continue;
			}
			$subDirectories[] = $directory->getFilename();
		}
		return $subDirectories;
	}
}