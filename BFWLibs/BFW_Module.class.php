<?php
/**
 * BFW_Module.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create on: 2011-05-22
 * $Id$
 */
abstract class BFW_Module {
	static private $modArr = array();

	public function __call($method, $params) {
		try {
			$method= ucfirst($method);
			$class = get_class($this) . '_' . $method;
			if (!self::$modArr[$class]) {
				if (!class_exists($class)) {
					$file = $this->modDir . '/' . $class . '.mod.php';
					if (!file_exists($file)) throw new Exception('The file "' . $file . '" not exists!' . "\n");
					require_once $file;
				}
				self::$modArr[$class] = BFW_Func::apcNewClass($class);
				foreach($this as $key => $val) self::$modArr[$class]->$key = $val;
			}
			call_user_func_array(array(self::$modArr[$class], 'run'), $params);
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
