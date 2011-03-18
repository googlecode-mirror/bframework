<?php
/**
 * BFW_Controler.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-20
 * $Id$
 */
final class BFW_Controler {
	static public function newModle() {
		$modTag = BFW_Config::get('site.modTag');
		$modArr = preg_split('/[_\/]/', trim(BFW_Request::get($modTag) ? BFW_Request::get($modTag) : 'index'));
		foreach($modArr as $k => $v) {
			if (!trim($v)) {
				unset($modArr[$k]);
			} else {
				$modArr[$k] = ucfirst($v);
			}
		}
		$mod = join('/', $modArr);
		try {
			if (!defined('MODULE_PATH'))  throw new Exception('Module file path has not bee defined! Please define the module path first!');
			$fName= (strstr($mod, '.')) ? $mod . '.php' : $mod . '.mod.php';
			$file = MODULE_PATH . '/' . $fName;
			if (!file_exists($file)) throw new Exception($file . ' not exists!');
			require_once $file;
			if (class_exists($mod)) {
				return BFW_Func::apc($mod);
			}
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}
