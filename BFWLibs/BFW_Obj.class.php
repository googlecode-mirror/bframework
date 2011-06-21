<?php
/**
 * BFW_Obj.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-21
 * $Id$
 */
final class BFW_Obj {
	static private $objArr = array();

	static public function set($class) {
		if (!isset(self::$objArr[$class])) {
			try {
				$objDir  = OBJECT_PATH . '/' . str_replace('_', '/', $class);
				$file = $objDir . '/Obj_' . $class . '.obj.php';
				if (!file_exists($file)) throw new Exception('The file "' . $file . '" not exists!' . "\n");
				self::$objArr[$class] = BFW_Func::apcNewClass('Obj_' . $class);
				self::$objArr[$class]->objDir = $objDir;
			} catch (Exception $e) {
				exit($e->getMessage());
			}
		}
		return self::$objArr[$class];
	}
}
