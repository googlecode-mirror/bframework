<?php
/**
 * BFW_String.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-20
 * $Id$
 */
final class BFW_String {
	static public function csubstr() {
	}

	static public function isMobi($str) {
		$p = '/^13\d{9}|^18[8-9]\d{8}|^15[289]\d{8}/';
		if (preg_match($p, $str)) {
			return true;
		}
	}

	static public function isPhone($str) {
		$p = '/^(\d{3,4}-)?\d{7,8}(-\d{3,5})?$/';
		if (preg_match($p, $str)) {
			return true;
		}
	}

	static public function quote($str) {
		if (!is_array($str)) {
			if (get_magic_quotes_runtime()) {
				return $str;
			} else {
				return addslashes($str);
			}
		} else {
			$values = array();
			foreach($str as $key => $val) {
				$values[$key] = self::quote($val);
			}
			return $values;
		}
	}
}
