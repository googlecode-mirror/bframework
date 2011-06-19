<?php
/**
 * BFW_Array.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-24
 * $Id$
 */
final class BFW_Array {
	static private $result = array();
	/**
	 * multisort
	 * @param array $array
	 * @param array $keys
	 * @return array
	 * @example
	 * $data = array(
	 *		array('id' => 1, 'name' => 'aaa'),
	 *		array('id' => 2, 'name' => 'bbb'),
	 *		array('id' => 3, 'name' => 'ccc'),
	 *		array('id' => 4, 'name' => 'ddd'),
	 * );
	 * multisort($data, array(array('key'=>'id', 'sort' => SORT_ASC, 'type' => SORT_STRING)));
	 */
	static public function multisort($array, $keys) {
		if (!is_array($array)) return false;
		foreach($array as $key => $val) {
			foreach($keys as $k) {
				$cols[$k['key']][$key] = $val[$k['key']];
			}
		}
		foreach($keys as $k) {
			$params[] = $cols[$k['key']];
			$params[] = (!isset($k['sort'])) ? SORT_ASC : $k['sort']; // SORT_ASC : SORT_DESC
			$params[] = (!isset($k['type'])) ? SORT_REGULAR : $k['type']; // SORT_REGULAR : SORT_STRING : SORT_NUMERIC
		}
		$params[] = &$array;

		call_user_func_array('array_multisort', $params);
		return $array;
	}

	/**
	 * merge
	 * @param array $data
	 * @param string $keyword
	 * @return array
	 * @example
	 * $data1 = array(array('id' => 1, 'name' => 'aaa'));
	 * $data2 = array(array('id' => 1, 'email' => 'aaa@aaa.aa'));
	 * $data3 = array(array('id' => 1, 'books' => array(array('bookId' => 1), array('bookId' => 2))));
	 * merge(array($data1, $data2, $data3, ...., $dataN), 'id');
	 */
	static public function merge($data, $keyword = null) {
		if (!is_array($data)) return $data;
		if ($keyword) {
			foreach($data as $key => $val) {
				foreach($val as $k => $v) {
					$tmp[$key][$v[$keyword]] = $v;
				}
			}
			foreach($tmp[0] as $rk1 => $rv1) {
				for ($i = 1; $i <= $key; $i ++) {
					if (isset($tmp[$i][$rk1])) {
						$rv1 = array_merge($rv1, $tmp[$i][$rk1]);
					}
				}
				$result[$rk1] = $rv1;
			}
			return $result;
		} else {
			$result = call_user_func_array('array_merge', $data);
		}
		return $result;
	}

	/**
	 * multi2single
	 * @param array $data
	 * @return mixed
	 */
	static public function multi2single($data) {
		if (!is_array($data)) return $data;
		foreach($data as $key => $val) {
			if (is_array($val)) {
				self::multi2single($val);
			} else {
				self::$result[] = $val;
			}
		}
		return self::$result;
	}

}
