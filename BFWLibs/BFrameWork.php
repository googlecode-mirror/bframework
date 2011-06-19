<?php
/**
 * autoload.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-20
 * $Id$
 */
if (!defined('MOD_TAG')) define('MOD_TAG', 'module');

function __autoload($class) {
	$pos = strpos($class, 'BFW_');
	if ($pos === false) {
		$includePath = explode(':', get_include_path());
		if (defined('MODULE_PATH')) $includePath[] = MODULE_PATH;
		if (defined('OBJECT_PATH')) $includePath[] = OBJECT_PATH;
		$replaceClass= substr($class, 4);
		$modFile = str_replace('_', '/', $replaceClass) . '/';
		foreach($includePath as $val) {
			if (file_exists($val . '/' . $class . '.php')) {
				require_once $class . '.php';
				break;
			} else if (file_exists($val . '/' . $class . '.class.php')) {
				require_once $class . '.class.php';
				break;
			} else if (file_exists($val . '/' . $modFile . $class . '.mod.php')) {
				require_once $val . '/' . $modFile . $class. '.mod.php';
				break;
			} else if (file_exists($val . '/' . $modFile . $class . '.obj.php')) {
				require_once $val . '/' . $modFile . $class . '.obj.php';
				break;
			}
		}
	} else {
		require_once BFWLIB_PATH . '/' . $class . '.class.php';
	}
}
