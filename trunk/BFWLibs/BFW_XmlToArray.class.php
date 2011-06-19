<?php
/**
 * BFW_XmlToArray.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-02-12
 * $Id$
 */
final class BFW_XmlToArray {
	static public function xmlToArray($str) {
		$parser = xml_parser_create();
		xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
		xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
		xml_parse_into_struct($parser, $str, $values, $index);
		xml_parser_free($parser);
		$i = 0;
		$name = $values[$i]['tag'];
		$array[$name] = isset($values[$i]['attributes']) ? $values[$i]['attributes'] : '';
		$array[$name] = self::structToArray($values, $i);
		return $array;
	}

	static public function arrayToXml() {
	}

	static private function structToArray($values, &$i) {
		$child = array();
		if (isset($values[$i]['value'])) array_push($child, $values[$i]['value']);
		while($i++ < count($values)) {
			switch ($values[$i]['type']) {
				case 'cdata':
					array_push($child, $values[$i]['value']);
				break;
				case 'complete':
					$name = $values[$i]['tag'];
					if(!empty($name)){
						if(isset($values[$i]['attributes'])) {
							$child[$name][] = array('value' => $values[$i]['value'], 'attributes' => $values[$i]['attributes']);
						} else {
							$child[$name][] = ($values[$i]['value'])?($values[$i]['value']):'';
						}
					}
				break;
				case 'open':
					$name = $values[$i]['tag'];
					// $size = isset($child[$name]) ? sizeof($child[$name]) : 0;
					// $child[$name][$size] = self::structToArray($values, $i);
					$child[$name] = self::structToArray($values, $i);
				break;
				case 'close':
					return $child;
				break;
			}
		}
		return $child;
	}
}

$str = file_get_contents('/media/wind/xml.xml');
$c = BFW_XmlToArray::xmlToArray($str);

$fp = fopen('/media/wind/xml_array.txt', 'w');
fwrite($fp, var_export($c, true));
fclose($fp);
