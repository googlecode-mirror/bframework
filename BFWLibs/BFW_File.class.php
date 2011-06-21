<?php
/**
 * BFW_File.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create on: 2011-06-19
 * $Id$
 */
final class BFW_File {
	static private $fp = array();

	public function __construct() {
		die('This class can\'t construct!');
	}

	static public function write($file, $content, $mode = 'w') {
		$dir = dirname($file);
		if (!is_dir($dir)) BFW_FileDir::createDir($dir);
		$fp = self::_getFP($file, $mode);
		fwrite($fp, $content);
		fclose($fp);
	}

	static public function read($file) {
	}

	static private function _getFP($file, $mode) {
		$fileMd5Str = md5($file);
		if (!isset(self::$fp[$fileMd5Str])) self::$fp[$fileMd5Str] = fopen($file, $mode);
		return self::$fp[$fileMd5Str];
	}
}
