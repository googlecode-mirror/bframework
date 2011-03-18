<?php
/**
 * Db.dao.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-23
 * $Id$
 */
final class DaoDb extends BFW_DBH {
	protected $table;
	protected $primaryKey;

	public function __construct($table) {
		parent::db('db');
		$this->table = self::$dbName['db'] . '.' . $table;
		$tableInfo = $this->getTableInfo();
		$this->primaryKey = $tableInfo['PRI'];
	}
}
