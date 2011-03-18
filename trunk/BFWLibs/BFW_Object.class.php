<?php
/**
 * BFW_Object.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-21
 * $Id$
 */
abstract class BFW_Object {
	private $objDir;

	public function __call($method, $params) {
		try {
			$class= get_class($this) . '_' . ucfirst($method);
			$file = $this->objDir . '/' . $class . '.php';
			if (!file_exists($file)) {
				throw new Exception('The file "' . $file . '" not exists!' . "\n");
			}
			require_once $file;
			$obj = new $class();
			foreach($this as $key => $val) {
				$obj->$key = $val;
			}
			call_user_func_array(array($obj, $method), $params);
		} catch (Exception $e) {
			exit($e->getMessage());
		}
	}

	public function __set($key, $val) {
		$this->$key = $val;
	}

	public function __get($key) {
		return $this->$key;
	}
}
