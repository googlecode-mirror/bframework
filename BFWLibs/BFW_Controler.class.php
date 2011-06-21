<?php
/**
 * BFW_Controler.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-20
 * $Id$
 */
final class BFW_Controler {
	static public function newModle() {
		// $modArr = preg_split('/[_\/.]/', trim(BFW_Request::get($modTag) ? BFW_Request::get($modTag) : 'index'));
		$modArr = explode('.', trim(BFW_Request::get(MOD_TAG) ? BFW_Request::get(MOD_TAG) : 'index'));
		foreach($modArr as $k => $v) {
			if (!trim($v)) unset($modArr[$k]); else $modArr[$k] = ucfirst($v);
		}
		$fun = end($modArr);
		if (isset($modArr[1])) array_pop($modArr);
		$mod = join('/', $modArr);
		try {
			$module  = 'Mod_' . str_replace('/', '_', $mod);
			if (class_exists($module)) {
				$modClass = BFW_Func::apcNewClass($module);
				$modClass->modDir = MODULE_PATH . '/' . $mod;
				return $modClass;
			}
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}
}
