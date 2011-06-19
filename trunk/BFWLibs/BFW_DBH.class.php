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
	static private $dbh = array();

	static public function db($db) {
		try {
			$dbConfig = BFW_Config::get('db.' . $db);
			$dsn = &$dbConfig['dsn'];
			$user = &$dbConfig['user'];
			$pass = &$dbConfig['pass'];
			$param= &$dbConfig['param'];

			if (!BFW_DB::$pdo[$db]) {
				$dsnMd5 = md5($dsn);
				if (!self::$dbh[$dsnMd5]) self::$dbh[$dsnMd5] = new PDO($dsn, $user, $pass, $param);
				BFW_DB::$pdo[$db] = self::$dbh[$dsnMd5];
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
		$sqlArr = parent::getSelectSql_($id, '*', $join);
		$dataObj = BFW_Data::load($sqlArr['sql'], $sqlArr['params'], $this->table, $this->primaryKey);
		return $dataObj;
	}

	public function getOne($id, $field = '*') {
		$sqlArr = parent::getSelectSql_($id, $field, $join = array());
		return parent::getOne_($sqlArr['sql'], $sqlArr['params']);
	}

	public function getRow($id, $field = '*', $join = null) {
		$sqlArr = parent::getSelectSql_($id, $field, $join);
		return parent::getRow_($sqlArr['sql'], $sqlArr['params']);
	}

	public function getAll($id, $field = '*', $join = null) {
		$sqlArr = parent::getSelectSql_($id, $field, $join);
		return parent::getAll_($sqlArr['sql'], $sqlArr['params']);
	}

	public function getPageAll($id, $field = '*', $join = null, $pageParam = array()) {
		$sqlArr = parent::getSelectSql_($id, $field, $join);
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
			$sqlArr = parent::getWhereSql_($id);
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
			return parent::query_($sqlStr, $params);
		}
	}

	public function delete($id) {
		$sqlArr = parent::getWhereSql_($id);
		return parent::query_('DELETE FROM ' .$this->table . $sqlArr['sql'], $sqlArr['params']);
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
				if(parent::query_($sql)) {
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
