<?php
/**
 * BFW_FileDir.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create on: 2011-06-18
 * $Id$
 */
final class BFW_FileDir {
	public function __construct() {
		die('This class can\'t construct!');
	}

	static public function createDir($dirName, $mode = 0755, $recursive = false) {
		if (!is_dir($dirName)) {
			$dirBreak   = (PHP_OS == 'WINNT') ? '\\' : '/';
			$linuxBreak = (PHP_OS != 'WINNT') ? '/'  : '';
			$dirArr = explode($dirBreak, $dirName);
			if ($dirArr) {
				foreach($dirArr as $dir) {
					$dir = trim($dir);
					if ($dir) {
						$dirPath .= $linuxBreak . $dir . $dirBreak;
						if (!is_dir($dirPath)) {
							if (!mkdir($dirPath, $mode, $recursive)) {
								echo 'create dir ' . $dirPath . ' failed' . "\n";
								return false;
							};
						}
					}
				}
				return true;
			}
		}
	}

	static public function deleteDir($dirName) {
	}

	static public function getFiles($dirName) {
	}
}
