<?php
/**
 * autoload.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-20
 * $Id$
 */
function __autoload($class) {
	if (strstr($class, 'BFW_')) {
		require_once BFWLIB_PATH . '/' . $class . '.class.php';
	} else {
		$includePath = explode(':', get_include_path());
		foreach($includePath as $val) {
			if (file_exists($val . '/' . $class . '.php')) {
				require_once $class . '.php';
				break;
			} else if (file_exists($val . '/' . $class . '.class.php')) {
				require_once $class . '.class.php';
				break;
			}
		}
	}
}
