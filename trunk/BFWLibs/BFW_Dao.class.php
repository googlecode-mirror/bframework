<?php
/**
 * BFW_Dao.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-21
 * $Id$
 */
final class BFW_Dao {
	static private $daoArr = array();

	static public function set($table) {
		if (!isset(self::$daoArr[$table])) {
			$tmpTable = ucfirst($table);
			$file = DAO_PATH . '/' . $tmpTable . '.dao.php';
			$class= 'Dao' . $tmpTable;
			if (!file_exists($file)) {
				$file = DAO_PATH . '/Db.dao.php';
				$class= 'DaoDb';
			}
			require_once $file;
			self::$daoArr[$table] = new $class($table);
		}
		return self::$daoArr[$table];
	}
}
