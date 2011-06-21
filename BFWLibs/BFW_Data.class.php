<?php
/**
 * BFW_Data.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-23
 * $Id$
 */
final class BFW_Data extends BFW_DB {
	protected $primaryKey;
	static private $data    = array();
	static private $setData = array();
	static private $objArr  = array();
	private $key;
	private $table;
	private $primaryId;

	static public function load($sql, $params, $table, $primaryKey) {
		$data = self::isHasData($params);
		if (!$data) {
			$data = parent::getRow_($sql, $params);
		}
		$primaryId = $data[$primaryKey];
		$key = $table . '_' . $primaryKey . '_' . $primaryId;
		if (!isset(self::$objArr[$key])) {
			self::$data[$key] = $data;
			self::$objArr[$key] = new BFW_Data($key, $table, $primaryKey, $primaryId);
		}
		return self::$objArr[$key];
	}

	public function __construct($key, $table, $primaryKey, $primaryId) {
		$this->key        = $key;
		$this->table      = $table;
		$this->primaryKey = $primaryKey;
		$this->primaryId  = $primaryId;
	}

	public function getData() {
		return self::$data[$this->key];
	}

	public function setData($key, $val) {
		self::$data[$this->key][$key] = $val;
		self::$setData[$this->key][$key] = $val;
	}

	public function update() {
		if (isset(self::$setData[$this->key])) {
			$sqlArr = parent::_getWhereSql($this->primaryId);
			foreach(self::$setData[$this->key] as $key => $val) {
				if (preg_match('/[\*\+\/\%-]|^CONCAT\((.*)[,].?(.*)\)$/i', $val)) {
					$fieldArr[] = $key . ' = ' . $val;
				} else {
					$fieldArr[] = $key . ' = ?';
					$valueArr[] = $val;
				}
			}
			unset(self::$setData[$this->key]);
			$params = BFW_Array::merge(array($valueArr, $sqlArr['params']));
			$sqlStr = 'UPDATE ' . $this->table . ' SET ' . join(',', $fieldArr) . $sqlArr['sql'] . "\n";
			return parent::query($sqlStr, $params);
		}
	}

	public function __get($key) {
		if (self::$data[$this->key][$key]) {
			return self::$data[$this->key][$key];
		}
	}

	public function __destruct() {
		$this->update();
	}

	static private function isHasData($params) {
		ksort($params);
		foreach($params as $k => $v) {
			$nk = str_replace(':', '', $k);
			$values[$nk] = $v;
		}
		if (self::$data) {
			foreach(self::$data as $val) {
				$arrayIntersect = array_intersect($values, $val);
				if ($arrayIntersect === $values) {
					return $val;
				}
			}
		}
	}
}
