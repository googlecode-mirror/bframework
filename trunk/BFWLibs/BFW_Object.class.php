<?php
/**
 * BFW_Object.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-21
 * $Id$
 */
abstract class BFW_Object {
	// private $objDir;
	static private $objArr = array();

	public function __call($method, $params) {
		try {
			$class = get_class($this) . '_' . ucfirst($method);
			if (!isset(self::$objArr[$class])) {
				$file = $this->objDir . '/' . $class . '.obj.php';
				if (!file_exists($file)) throw new Exception('The file "' . $file . '" not exists!' . "\n");
				require_once $file;
				self::$objArr[$class] = BFW_Func::apcNewClass($class);
				foreach($this as $key => $val) self::$objArr[$class]->$key = $val;
			}
			call_user_func_array(array(self::$objArr[$class], 'run'), $params);
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	public function __set($key, $val) {
		$this->$key = $val;
	}

	public function __get($key) {
		return $this->$key;
	}
}
