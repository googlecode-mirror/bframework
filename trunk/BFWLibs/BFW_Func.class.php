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

	static public function apc($class) {
		if (extension_loaded('apc')) {
			$modClass = apc_fetch($class);
			if (!$modClass) {
				$modClass = new $class;
				apc_add($class, $modClass);
			}
		} else {
			$modClass = new $class;
		}
		return $modClass;
	}
}
