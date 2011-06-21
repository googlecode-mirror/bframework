<?php
/**
 * BFW_Memcache.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create on: 2011-05-27
 * $Id$
 */
final class BFW_Memcache {
	static private $memcache;

	static public function add($key, $val, $flag, $expire) {
		self::_addServer();
		return self::$memcache->add($key, $val, $flag, $expire);
	}

	static public function get($key) {
		self::_addServer();
		$val = self::$memcache->get($key);
		self::_get($val);
		return $val;
	}

	static public function set($key, $val, $flag = false, $expire = 0) {
		self::_addServer();
		self::$memcache->set($key, $val, $flag, $expire);
	}

	static public function replace($key, $val, $flag = false, $expire = 0) {
		self::_addServer();
		self::$memcache->replace($key, $val, $flag, $expire);
	}

	static public function del($key, $timeout = 0) {
		self::_addServer();
		self::$memcache->delete($key, $timeout);
	}

	static public function getCacheInfo() {
		self::_addServer();
		$cacheInfo['extendStats'] = self::$memcache->getExtendedStats();
		$cacheInfo['stats'] = self::$memcache->getStats();
		return $cacheInfo;
	}

	static private function _addServer() {
		if (!self::$memcache) {
			$memcacheHost = BFW_Config::get('site.cacheServer.host');
			self::$memcache = new Memcache;
			foreach($memcacheHost as $val) self::$memcache->addServer($val['host'], $val['port']);
		}
	}

	static private function _get(&$val) {
		if(is_array($val)) {
			foreach($val as $k => &$v) {
				if (is_array($v)) {
					self::_get($v);
				} else {
					$v = @unserialize($v) ? : $v;
				}
			}
		} else {
			$val = @unserialize($val) ? : $val;
		}
	}
}
