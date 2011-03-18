<?php
/**
 * BFW_Curl.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-02-21
 * $Id$
 */
final class BFW_Curl {
	static private $curlHandle = null;
	static private $curlInit   = array();
	static private $httpHeader = array();

	static public function curl($url) {
		self::_curlInit($url);
		self::_setCurlOpt();
		self::_getUrlContents();
	}

	static public function setPost($data) {
	}

	static public function setHttpHeader($host, $referer) {
		self::$httpHeader = array(
			'Host'            => $host,
			//'User-Agent'      => 'Mozilla/5.0 (X11; U; Linux i686; zh-CN; rv:1.9.0.13) Gecko/2009080315 Ubuntu/9.04 (jaunty) Firefox/3.0.13',
			'User-Agent'      => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.1.6) Gecko/20091201 Firefox/3.5.6 (.NET CLR 3.5.30729)',
			'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
			'Accept-Language' => 'zh-cn,en-us;q=0.7,en;q=0.3',
			'Accept-Encoding' => 'gzip,deflate',
			'Accept-Charset'  => 'gb2312,utf-8;q=0.7,*;q=0.7',
			'Keep-Alive'      => '300',
			'Connection'      => 'keep-alive',
			'Referer'         => $referer
		);
	}

	static private function _curlInit($url) {
		if (!is_array($url)) {
			self::$curlInit[] = curl_init($url);
		} else {
			foreach($url as $v) {
				self::$curlInit[] = curl_init($v);
			}
		}
	}

	static private function _setCurlOpt() {
		self::$curlHandle = curl_multi_init();
		foreach(self::$curlInit as $v) {
			curl_setopt($v, CURLOPT_HEADER, true);
			// curl_setopt($v, CURLOPT_HTTPHEADER, self::$httpHeader);
			curl_setopt($v, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($v, CURLOPT_AUTOREFERER, true);
			curl_multi_add_handle(self::$curlHandle, $v);
		}
	}

	static private function _getUrlContents() {
		$run = null;
		do {
			curl_multi_exec(self::$curlHandle, $run);
		} while ($run > 0);
		echo $run;
		foreach(self::$curlInit as $k => $v) {
			echo curl_multi_getcontent($v);
			curl_multi_remove_handle(self::$curlHandle, $v);
		}
		curl_multi_close(self::$curlHandle);
		return $text;
	}
}

BFW_Curl::curl(array('http://www.sina.com.cn','http://www.taobao.com'));
