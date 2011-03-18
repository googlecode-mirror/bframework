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
		if (!self::$objArr[$class]) {
			try {
				if (!strstr($class, '_')) {
					$objDir  = OBJECT_PATH . '/' . $class;
					$file = $objDir . '/' . $class . '.obj.php';
					if (!file_exists($file)) {
						throw new Exception('The file "' . $file . '" not exists!' . "\n");
					}
					require_once $file;
					$className = $class;
				} else {
					$classArr = explode('_', $class);
					$objDir   = OBJECT_PATH . '/';
					foreach ($classArr as $val) {
						$objDir .= $val . '/';
						$file = $objDir . $val . '.obj.php';
						if (!file_exists($file)) {
							throw new Exception('The file "' . $file . '" not exists!' . "\n");
						}
						require_once $file;
					}
					$className = end($classArr);
				}
				self::$objArr[$class] = BFW_Func::apc($class);
				self::$objArr[$class]->objDir = $objDir;
			} catch (Exception $e) {
				exit($e->getMessage());
			}
		}
		return self::$objArr[$class];
	}
}
