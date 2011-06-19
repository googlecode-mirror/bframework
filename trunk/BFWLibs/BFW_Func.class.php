<?php
/**
 * BFW_Func.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-22
 * $Id$
 */
final class BFW_Func {
	static public function getMicroTime() {
		list($usec, $sec) = explode(' ', microtime());
		return ((float)$usec + (float)$sec);
	}

	static public function convertMemorySize($size, $precision = 2) {
		$sizeName = array('Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		return $size ? round($size / pow(1024, ($i = floor(log($size, 1024)))), $precision) . ' ' . $sizeName[$i] : '0 Bytes';
	}

	static public function getUsedMemory($initMemory, $method) {
		$finalMemory = memory_get_usage();
		$size = $finalMemory - $initMemory;
		$peakUsage = memory_get_peak_usage();
		$peakSize  = $peakUsage - $initMemory;
		echo "<br />\n";
		echo $method . ' memory used: ' . BFW_Func::convertMemorySize($size) . "<br />\n";
		echo $method . ' peak memory used: ' .BFW_Func::convertMemorySize($peakSize) . "<br />\n";
		echo "<br />\n";
	}

	static public function apcNewClass($class, $param = null) {
		$modClass = BFW_APC::get($class);
		if (!$modClass) {
			$modClass = new $class($param);
			BFW_APC::set($class, $modClass);
		}
		return $modClass;
	}
}
