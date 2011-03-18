<?php
/**
 * BFW_Config.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-21
 * $Id$
 */
final class BFW_Config {
	static private $value = array();

	/**
	 * 取得配置文件值
	 * @param string $key
	 * @return mixed
	 */
	static public function get($key) {
		if (strstr($key, '.')) {
			$arr = explode('.', $key);
			$f   = str_replace('_', '/', $arr[0]);
			$key = str_replace($arr[0] . '.', '', $key);
		} else {
			$f   = str_replace('_', '/', $key);
			$key = null;
		}
		if (!isset(self::$value[$f])) {
			$file = CONFIG_PATH . '/' . $f . '.conf.php';
			if (file_exists($file)) {
				self::$value[$f] = require_once $file;
			} else {
				die('PHP not find the config file: "' . $file . '"');
			}
		}
		return self::_get(self::$value[$f], $key);
	}

	static public function setVal($key, $val) {
		self::$value[$key] = $val;
	}

	static public function getVal($key) {
		if (isset(self::$value[$key])) {
			return self::$value[$key];
		}
	}

	/**
	 * 取得配置文件中指定值
	 * @param array $value
	 * @param string $key
	 * @return mixed
	 */
	static private function _get($value, $key) {
		if ($key) {
			if (strstr($key, '.')) {
				$keyArr = explode('.', $key);
				foreach($keyArr as $v) {
					if (is_array($value[$v])) {
						$newKey = str_replace($v . '.', '', $key);
						$newVal = self::_get($value[$v], $newKey);
					}
					return $newVal;
				}
			} else {
				return $value[$key];
			}
		}
		return $value;
	}

}
