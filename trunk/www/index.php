<?php
/**
 * index.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-20
 * $Id$
 */
if (!defined('BFWLIB_PATH')) define('BFWLIB_PATH', '/var/www/BFrameWork/BFWLibs');
if (!defined('MODULE_PATH')) define('MODULE_PATH', '/var/www/BFrameWork/Module');
if (!defined('OBJECT_PATH')) define('OBJECT_PATH', '/var/www/BFrameWork/Object');
if (!defined('CONFIG_PATH')) define('CONFIG_PATH', '/var/www/BFrameWork/Conf');
if (!defined('DAO_PATH'))    define('DAO_PATH', '/var/www/BFrameWork/Dao');
if (!defined('TPL_PATH'))    define('TPL_PATH', '/var/www/BFrameWork/Tpl');

require_once BFWLIB_PATH . '/BFrameWork.php';

BFW_App::run();
