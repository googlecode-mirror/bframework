<?php
/**
 * mysql.conf.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-21
 * $Id$
 */
return array(
	'db' => array(
		'dsn'    => 'mysql:host=localhost;dbname=test',
		'dbName' => 'test',
		'user'   => 'root',
		'pass'   => '123qwe',
		'param'  => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'),
	),
	'user' => array(
		'dsn'    => 'mysql:host=localhost;dbname=test_1',
		'dbName' => 'test_1',
		'user'   => 'root',
		'pass'   => '123qwe',
		'param'  => array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'),
	),

);
