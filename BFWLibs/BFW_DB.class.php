<?php
/**
 * BFW_DB.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-21
 * $Id$
 */
abstract class BFW_DB {
	static protected $pdo = array();
	static protected $sqlStrArr= array();
	static protected $db;
	static private   $sth;
	static private   $sqlDebug = false;
	static private   $dataFromDb = array();

	static protected function getOne_($sql, $params = array(), $index = 0) {
		$key = md5(__FUNCTION__ . $sql . join('', $params));
		if (!self::$dataFromDb[$key]) {
			self::_execute($sql, $params);
			self::$dataFromDb[$key] = self::$sth->fetchColumn($index);
			self::$sth->closeCursor();
		}
		return self::$dataFromDb[$key];
	}

	static protected function getRow_($sql, $params = array()) {
		$key = md5(__FUNCTION__ . $sql . join('', $params));
		if (!self::$dataFromDb[$key]) {
			self::_execute($sql, $params);
			self::$dataFromDb[$key] = self::$sth->fetch(PDO::FETCH_ASSOC);
			self::$sth->closeCursor();
		}
		return self::$dataFromDb[$key];
	}

	static protected function getAll_($sql, $params = array()) {
		$key = md5(__FUNCTION__ . $sql . join('', $params));
		if (!self::$dataFromDb[$key]) {
			self::_execute($sql, $params);
			self::$dataFromDb[$key] = self::$sth->fetchAll(PDO::FETCH_ASSOC);
			self::$sth->closeCursor();
		}
		return self::$dataFromDb[$key];
	}

	static protected function query_($sql, $params = array()) {
		self::_execute($sql, $params);
		return self::$sth->rowCount();
	}

	static protected function getTableInfo_($table) {
		$rs = self::getAll_('DESC ' . $table);
		foreach($rs as $key => $val) {
			$r[$key]['Field'] = $val['Field'];
			$r[$key]['Type']  = $val['Type'];
			$r[$key]['Null']  = $val['Null'];
			$r[$key]['Key']   = $val['Key'];
			if ('PRI' === $val['Key']) {
				$r['PRI'] = $val['Field'];
			}
		}
		return $r;
	}

	static private function _getExpain($sql) {
		$sth = self::$pdo[self::$db]->prepare($sql);
		$sth->execute();
		$rs = $sth->fetchAll(PDO::FETCH_ASSOC);
		$sth->closeCursor();
		return $rs;
	}

	static private function _execute($sql, $params) {
		try {
			if (!self::$sqlDebug) self::$sqlDebug = BFW_Request::get('sqlDebug');
			if (self::$sqlDebug) $startTime = BFW_Func::getMicroTime();
			self::$sth = self::$pdo[self::$db]->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
			self::$sth->execute($params);
			if (self::$sqlDebug) {
				$endTime = BFW_Func::getMicroTime();
				$times   = $endTime - $startTime;
				if (preg_match('/^SELECT/i', $sql)) {
					$sql = self::_getSelectSqlString($sql, $params);
					$explain = self::_getExpain('EXPLAIN ' . $sql);
					BFW_App::$appGlobal['sqlDebug'][self::$db][] = array('sql' => $sql, 'explain' => $explain, 'time' => number_format($times, 10));
				}
			}
		} catch (PDOException $e) {
			$traceArr = $e->getTrace();
			$content  = '[' . date('Y-m-d H:i:s') . ']' . "\n";
			foreach($traceArr as $k => $v) {
				$content .= '# Error in file "' . $v['file'] . '" on line "' . $v['line'] . "\"\n";
			}
			$content .= '# ' . $e->getMessage() . "\n" . '# SQL String: ' . $sql . "\n\n";
			die($content);
		}
	}

	protected function getSelectSql_($id, $field, $join) {
		$params = array();
		$sql = 'SELECT ' . $field . ' FROM ' . $this->table;
		$sql .= self::_getJoin($join);
		$sqlArr = $this->getWhereSql_($id);
		return array('sql' => $sql . $sqlArr['sql'], 'params' => $sqlArr['params']);
	}

	protected function getWhereSql_($id) {
		$sql = ' WHERE ';
		if (!is_array($id)) {
			$sql .= $this->primaryKey . ' = ?';
			$params[] = $id;
		} else {
			if ($id['sql']) {
				$sql .= $id['sql'];
				if ($id['par']) $params = $id['par'];
			} else {
				foreach($id as $key => $val) {
					$whereArr[] = $key . ' = :' . $key;
					$params[':' . $key] = $val;
				}
				$sql .= join(' AND ', $whereArr);
			}
		}
		return array('sql' => $sql, 'params' => $params);
	}

	static private function _getJoin($join) {
		if ($join) {
			$sql = ' AS main ';
			if (!is_array($join)) {
				$sql .= $join;
			}
			try {
				foreach($join as $val) {
					if ($val['table'] && $val['alias'] && $val['condition']) {
						$joinArr[] = 'LEFT JOIN ' . $val['table'] . ' AS ' . $val['alias'] . ' ' . $val['condition'];
					} else {
						throw new Exception('The join format must be: array(array(\'table\' => \'tableName\', \'alias\' => \'aliasName\', \'condition\' => \'USING(fieldName)\'))');
					}
				}
				$sql .= join(',', $joinArr);
			} catch (Exception $e) {
				die($e->getMessage());
			}
		}
		return $sql;
	}

	static private function _getSelectSqlString($sql, $params) {
		if (!$params) {
			return $sql;
		} else {
			$leng = strlen($sql);
			$strA = explode('?',$sql);
			if (strlen($strA[0]) < $leng) {
				$sql = '';
				foreach($strA as $k => $v) {
					if (isset($params[$k])) {
						$val = (is_int($params[$k])) ? $params[$k] : '\'' . $params[$k] . '\'';
						$sql .= $v . $val;
					} else {
						$sql .= $v;
					}
				}
			} else {
				$keys = array_keys($params);
				$vals = array_values($params);
				foreach($vals as $key => $val) {
					$vals[$key] = (is_int($val)) ? $val : '\'' .$val . '\'';
				}
				$sql  = str_replace($keys, $vals, $sql);
			}
			return $sql;
		}

	}
}
