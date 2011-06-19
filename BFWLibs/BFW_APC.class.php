<?php
/**
 * BFW_APC.class.php
 * @author JunboBao <baojunbo@gmail.com>
 * @create on: 2011-05-27
 * $Id$
 */
final class BFW_APC {
	static public function get($key, $default = null) {
		if (extension_loaded('apc')) {
			return apc_fetch($key) ? : $default;
		}
	}

	static public function set($key, $val) {
		if (extension_loaded('apc')) {
			if (!self::get($key)) {
				apc_add($key, $val);
			} else {
				apc_store($key, $val);
			}
		}
	}
}
