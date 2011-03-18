<?php
/**
 * User.dao.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-23
 * $Id$
 */
final class DaoUser extends BFW_DBH {
	protected $table;
	protected $primaryKey = 'u_id';

	public function __construct($daoName) {
		parent::db($daoName);
		$this->table = self::$dbName[$daoName] . '.user_info';
	}
}
