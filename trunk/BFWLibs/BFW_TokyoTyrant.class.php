<?php
/**
 * TokyoTyrant.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create on: 2011-05-29
 * $Id$
 */
final class BFW_TokyoTyrant {
	static private $tt = array();
	static private $ttServer = array();

	static public function add($key, $increment, $type) {
	}

	static public function get($key) {
		$val = self::_selectServer($key)->get($key);
		$val = unserialize($val) ? : $val;
		return $val;
	}

	static public function set($key, $val) {
		if (is_array($val)) $val = serialize($val);
		self::_selectServer($key)->put($key, $val);
	}

	static public function del($key) {
		// self::_selectServer($key)->out($key);
		self::_selectServer($key)->put($key, '');
	}

	static public function getCacheInfo() {
		foreach(self::$tt as $key => $val) {
			$statistics[$key] = $val->stat();
		}
		return $statistics;
	}

	static private function _selectServer($key) {
		$md5Key = md5($key);
		self::_createTTLink($md5Key[0]);
		return self::$tt[$md5Key[0]];
	}

	static private function _createTTLink($suffix) {
		if (!self::$ttServer) self::$ttServer = BFW_Config::get('site.cacheServer.host');
		if (!self::$tt[$suffix]) {
			self::$tt[$suffix] = new TokyoTyrant(self::$ttServer[$suffix]['host'], self::$ttServer[$suffix]['port']);
		}
	}
}
