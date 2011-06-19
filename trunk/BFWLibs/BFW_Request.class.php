<?php
/**
 * BFW_Request.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-20
 * $Id$
 */
final class BFW_Request {
	static public function get($key, $default = null) {
		return self::_get(__FUNCTION__, $key, $default);
	}

	static public function getx() {
		$keys = func_get_args();
		return self::_getx(__FUNCTION__, $keys);
	}

	static public function post($key, $default = null) {
		return self::_get(__FUNCTION__, $key, $default);
	}

	static public function postx() {
		$keys = func_get_args();
		return self::_getx(__FUNCTION__, $keys);
	}

	static public function server($key, $default = null) {
		return self::_get(__FUNCTION__, $key, $default);
	}

	static public function serverx() {
		$keys = func_get_args();
		return self::_getx(__FUNCTION__, $keys);
	}

	static private function _get($type, $key, $default) {
		$data = self::_getData($type);
		return (array_key_exists($key, $data)) ? $data[$key] : $default;
	}

	static private function _getx($type, $keys) {
		$result = array();
		$data = self::_getData($type);
		if (!$keys) return $data;
		foreach ($keys as $key) $result[$key] = $data[$key];
		return $result;
	}

	static private function _getData($type) {
		if ($type == 'get' || $type == 'getx') $data = $_GET;
		if ($type == 'post' || $type == 'postx') $data = $_POST;
		if ($type == 'server' || $type == 'serverx') $data = $_SERVER;
		return $data;
	}
}
