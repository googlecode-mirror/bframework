<?php
/**
 * BFW_DBH.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-21
 * $Id$
 */
abstract class BFW_DBH extends BFW_DB {
	static protected $dbName = array();
	static private $data = array();

	static public function db($db) {
		try {
			$dbConfig = BFW_Config::get('db.' . $db);
			if (!BFW_DB::$pdo[$db]) {
				BFW_DB::$pdo[$db] = new PDO($dbConfig['dsn'], $dbConfig['user'], $dbConfig['pass'], $dbConfig['param']);
				BFW_DB::$pdo[$db]->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				BFW_DB::$db = $db;
			}
			if (!self::$dbName[$db]) {
				self::$dbName[$db] = $dbConfig['dbName'];
			}
		} catch (PDOException $e) {
			$traceArr = $e->getTrace();
			$content  = '[' . date('Y-m-d H:i:s') . ']' . "\n";
			foreach($traceArr as $k => $v) {
				$content .= '# Error in file "' . $v['file'] . '" on line "' . $v['line'] . "\"\n";
			}
			$content .= '# ' . $e->getMessage() . "\n" . '# SQL String: ' . $sql . "\n\n";
			die('PDO Error: ' . $e->getMessage() . "\n");
		}
	}

	public function load($id, $join = null) {
		$sqlArr = $this->_getSelectSql($id, '*', $join);
		return BFW_Data::load($sqlArr['sql'], $sqlArr['params'], $this->table, $this->primaryKey);
	}

	public function getOne($id, $field = '*') {
		$sqlArr = $this->_getSelectSql($id, $field, $join = array());
		return parent::getOne_($sqlArr['sql'], $sqlArr['params']);
	}

	public function getRow($id, $field = '*', $join = null) {
		$sqlArr = $this->_getSelectSql($id, $field, $join);
		return parent::getRow_($sqlArr['sql'], $sqlArr['params']);
	}

	public function getAll($id, $field = '*', $join = null) {
		$sqlArr = $this->_getSelectSql($id, $field, $join);
		return parent::getAll_($sqlArr['sql'], $sqlArr['params']);
	}

	public function getPageAll($id, $field = '*', $join = null, $pageParam = array()) {
		$sqlArr = $this->_getSelectSql($id, $field, $join);
		return BFW_Pager::page($sqlArr['sql'], $sqlArr['params'], $pageParam);
	}

	public function setData($key, $val = null) {
		if ($val && !is_array($key)) {
			self::$data[$this->table][$key] = $val;
		} else if (is_array($key)) {
			foreach($key as $k => $v) {
				if (!is_array($v)) self::$data[$this->table][$k] = $v;
			}
		}
	}

	public function update($id) {
		if (self::$data[$this->table]) {
			$sqlArr = $this->_getWhereSql($id);
			foreach(self::$data[$this->table] as $key => $val) {
				if (preg_match('/[\*\+\/\%-]|^CONCAT\((.*)[,].?(.*)\)$/i', $val)) {
					$fieldArr[] = $key . ' = ' . $val;
				} else {
					$fieldArr[] = $key . ' = ?';
					$valueArr[] = $val;
				}
			}
			unset(self::$data[$this->table]);
			$params = BFW_Array::merge(array($valueArr, $sqlArr['params']));
			$sqlStr = 'UPDATE ' . $this->table . ' SET ' . join(',', $fieldArr) . $sqlArr['sql'] . "\n";
			return parent::query($sqlStr, $params);
		}
	}

	public function delete($id) {
		$sqlArr = $this->_getWhereSql($id);
		return $this->query('DELETE FROM ' .$this->table . $sqlArr['sql'], $sqlArr['params']);
	}

	public function insert($data) {
		try {
			if (!is_array($data)) {
				throw new Exception('$data is not array!');
			}
			if ($data['key']) $keyStr = ' (' . join(',', $data['key']) . ')';
			if (is_array($data['value'])) {
				foreach($data['value'] as $val) {
					if (is_array($val)) {
						foreach($val as $k => $v) {
							$val[$k] = (is_int($v)) ? $v : '\'' . $v . '\'';
						}
						$values[] = '(' . join(',', $val) . ')';
					} else {
						$value[] = (is_int($val)) ? $val : '\'' . $val . '\'';
					}
				}
				$valueStr = ($values) ? join(',', $values) : '(' . join(',', $value) . ')';
				$sql = 'INSERT INTO ' . $this->table . $keyStr . ' VALUES ' . $valueStr;
				if($this->query($sql)) {
					return BFW_DB::$pdo[BFW_DB::$db]->lastInsertId();
				}
			}
		} catch (Exception $e) {
			die($e->getMessage());
		}
	}

	public function getTableInfo() {
		return parent::getTableInfo_($this->table);
	}
}
