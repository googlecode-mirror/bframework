<?php
/**
 * BFW_App.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-20
 * $Id$
 */
final class BFW_App {
	static public $appGlobal = array();

	static public function run() {
		$initMemory = memory_get_usage();

		$mod    = BFW_Request::get(MOD_TAG);
		if ($mod) {
			$modArr = explode('.', $mod);
			$module = end($modArr);
		}
		$method = (strstr($mod, '.')) ? $module : __FUNCTION__;
		$module = BFW_Controler::newModle();
		if ($module) $module->$method();
		self::_getSqlDebug();
		self::_getIncludeFile();

		BFW_Func::getUsedMemory($initMemory, get_class($module) . '::' . $method);
	}

	static private function _getSqlDebug() {
		$times = 0;
		if (isset(self::$appGlobal['sqlDebug'])) {
			$string = '<style type="text/css">table.sqlDebug {width:auto;border-collapse: collapse;border-width:1px 0 0 1px; border-style:solid;border-color:gray;margin-top:5px;}';
			$string .= 'table.sqlDebug caption {font-weight:bold;font-size:16px;background-color:gray;padding:5px;}';
			$string .= 'table.sqlDebug th, table.sqlDebug td {border-width:0 1px 1px 0;border-style:solid;border-color:gray;padding:4px;}table.sqlDebug th {font-size:14px;background-color:#ccc;}';
			$string .= 'table.sqlDebug td {font-size:12px;}</style><table class="sqlDebug" cellspacing="1" cellpadding="3"><caption>SQL DEBUG</caption><tr><th>ID</th><th>SQL</th><th>TIME</th></tr>';
			foreach(self::$appGlobal['sqlDebug'] as $key => $dbSql) {
				if ($dbSql) {
					foreach($dbSql as $val) {
						$thStr = '<tr><th>id</th><th>select_type</th><th>table</th><th>type</th><th>possible_keys</th><th>key</th><th>key_len</th><th>ref</th><th>rows</th><th>Extra</th></tr>';
						$sqlString = '<div class="sqlDiv">' . $val['sql'] . '</div>';
						$sqlString .= '<div><table class="sqlDebug" style="width:100%" cellspacing="1" cellpadding="3">' . $thStr;
						foreach($val['explain'] as $k => $v) {
							$k += 1;
							$sqlString .= '<tr><td>' . $k . '</td><td>' . $v['select_type'] . '</td><td>' . $v['table'] . '</td><td>' . $v['type'] . '</td><td>' . $v['possible_keys'] . '</td><td>' . $v['key'] . '</td><td>' . $v['key_len'] . '</td><td>' . $v['ref'] . '</td><td>' . $v['rows'] . '</td><td>' . $v['Extra']. '</td></tr>';
						}
						$sqlString .= '</table></div>';
						$key += 1;
						$string .= '<tr><td>' . $key . '</td><td>' . $sqlString . '</td><td>' . $val['time'] . '</td></tr>';
						unset($fields);
						unset($values);
						$times += $val['time'];
					}
				}
			}
			$string .= '<tr><td colspan="3" style="text-align: right;">Total time: ' . $times . '</td></tr>';
			$string .= '</table>';
			echo $string;
		}
	}

	static private function _getIncludeFile() {
		$includeFiles = get_included_files();
		print '<pre>';
		// print_r(apc_cache_info());
		print_r($includeFiles);
		print '</pre>';
	}
}
