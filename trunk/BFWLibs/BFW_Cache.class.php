<?php
/**
 * BFW_Cache.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create on: 2011-05-27
 * $Id$
 */
final class BFW_Cache {
	static private $cacheType;

	static public function add($key, $val, $flag = false, $expire = 0) {
		if ($key) return self::_callMethod(__FUNCTION__, array($key, $val, $flag, $expire));
	}

	static public function get($key) {
		if ($key) return self::_callMethod(__FUNCTION__, array($key));
	}

	static public function set($key, $val, $flag = false, $expire = 0) {
		if ($key) self::_callMethod(__FUNCTION__, array($key, $val, $flag, $expire));
	}

	static public function replace($key, $val, $flag = false, $expire = 0) {
		if ($key) self::_callMethod(__FUNCTION__, array($key, $val, $flag, $expire));
	}

	static public function del($key, $timeout = 0) {
		if ($key) self::_callMethod(__FUNCTION__, array($key, $timeout));
	}

	static public function getCacheInfo() {
		return self::_callMethod(__FUNCTION__, array());
	}

	static private function _callMethod($method, $params) {
		self::_getCacheType();
		return call_user_func_array(array('BFW_' . self::$cacheType, $method), $params);
	}

	static private function _getCacheType() {
		if (!self::$cacheType) {
			$cacheType = BFW_Config::get('site.cacheServer.type');
			if (!$cacheType) {
				self::$cacheType = 'Memcache';
			} else {
				self::$cacheType = ucfirst($cacheType);
			}
		}
	}
}
