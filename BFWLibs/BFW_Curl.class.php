<?php
/**
 * BFW_Curl.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-02-21
 * $Id$
 */
final class BFW_Curl {
	static public $timeOut        = 30;
	static public $maxRedirecs    = 4;
	static public $binaryTransfer = false;
	static public $includeHeader  = false;
	static public $noBody         = false;
	static public $authEntication = false;
	static private $cookie     = null;
	static private $post       = null;
	static private $httpHeader = null;
	static private $webContent = null;
	static private $urlSuffix  = null;

	static public function sendUrl($url) {
		if (!is_array($url)) {
			self::_singleUrl($url);
			return self::_getContent();
		} else {
			return self::_multiUrl($url);
		}
	}

	static public function setHttpHeader($host, $referer) {
		self::$httpHeader = array(
			'Host'            => $host,
			//'User-Agent'      => 'Mozilla/5.0 (X11; U; Linux i686; zh-CN; rv:1.9.0.13) Gecko/2009080315 Ubuntu/9.04 (jaunty) Firefox/3.0.13',
			'User-Agent'      => 'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.1.6) Gecko/' . date('Ymdhi') . ' Firefox/3.5.6 (.NET CLR 3.5.30729)',
			'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
			'Accept-Language' => 'zh-cn,en-us;q=0.7,en;q=0.3',
			'Accept-Encoding' => 'gzip,deflate',
			'Accept-Charset'  => 'gb2312,utf-8;q=0.7,*;q=0.7',
			'Keep-Alive'      => '300',
			'Connection'      => 'keep-alive',
			'Referer'         => $referer
		);
	}

	static public function setPost($postFields, $m = 'post') {
		if (is_array($postFields)) {
			$queryStr = http_build_query($postFields);
			if ($m != 'post') {
				self::$urlSuffix = $queryStr;
			} else {
				self::$post = $queryStr;
			}
		}
	}

	static private function _getContent() {
		if (self::$webContent) {
			return self::$webContent;
		}
	}

	static private function _singleUrl($url) {
		$curl = self::_initCurl($url);
		self::$webContent = curl_exec($curl);
		$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
		$errno  = curl_errno($curl);
		$error  = curl_error($curl);
		try {
			if ($status != 200 && $errno) throw new Exception('curl error: ' . $errno . $error);
		} catch(Exception $e) {
			die($e->getMessage());
		}
	}

	static private function _multiUrl($url) {
		$handle = curl_multi_init();
		foreach($url as $k => $v) {
			$curl[$k] = self::_initCurl($v);
			curl_multi_add_handle($handle, $curl[$k]);
		}
		$flag = null;
		do {
			curl_multi_exec($handle, $flag);
		} while($flag > 0);
		foreach($curl as $ck => $cv) {
			$text[$ck] = curl_multi_getcontent($cv);
			curl_multi_remove_handle($handle, $cv);
		}
		curl_multi_close($handle);
		return $text;
	}

	static private function _initCurl($url) {
		$urlScheme = parse_url($url, PHP_URL_SCHEME);
		$s = curl_init($url . self::$urlSuffix);
		curl_setopt($s, CURLOPT_HTTPHEADER, self::$httpHeader);
		curl_setopt($s, CURLOPT_TIMEOUT, self::$timeOut);
		curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($s, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($s, CURLOPT_MAXREDIRS, self::$maxRedirecs);
		if ($urlScheme == 'https') {
			curl_setopt($s, CURLOPT_VERBOSE, 1);
			curl_setopt($s, CURLOPT_SSL_VERIFYHOST, 2);
			curl_setopt($s, CURLOPT_SSL_VERIFYPEER, false);
		}
		if (self::$post) {
			curl_setopt($s, CURLOPT_POST, true);
			curl_setopt($s, CURLOPT_POSTFIELDS, self::$post);
		}
		if (self::$authEntication == 1) curl_setopt($s, CURLOPT_USERPWD, self::$authName . ':' . self::$authPass);
		if (self::$includeHeader) curl_setopt($s, CURLOPT_HEADER, true);
		if (self::$noBody) curl_setopt($s, CURLOPT_NOBODY, true);
		if (self::$cookie) curl_setopt($s, CURLOPT_COOKIE, self::$cookie);
		return $s;
	}
}

/*
BFW_Curl::setHttpHeader('www.taobao.com', 'www.taobao.com');
$content = BFW_Curl::sendUrl(array('http://www.taobao.com', 'http://www.sina.com'));
$fp = fopen('/tmp/a.txt', 'w');
fwrite($fp, var_export($content, true));
fclose($fp);
$postFields = array(
	'target'   => 'Activity_ValentineDay_RankTop.getHarvestRose',
	'response' => '1',
	'data'     => array(
		'hashKey'  => 'ZjM1OW9NRmpJSi81NmtBUXZ2S0tDZ05uNmZFdEx6d0JYYWl6VjZzNXJsOEo3VC9mb3lGWUdJdzFFU1M3bVNwUDBaREFOQVhqYysvcDZaZ3hoVzBLWVN2YnpaZW5VZTJuMXdpQ01mZ0pFaHd3YWRmRzIyWlh1MzdoWGcwOWVhblVGSVVRRXVUUlRzQ2phRjdnY0FCK3A5czRDLzhrV1FKN1BadVlwbS8xWk9zL3JKd1hPZG5LTUVkQStBOUZxOGNDekxwY1c5dzN0d1hJM2JFcWJmUXhPQmk1cnM4YThZRW91VURvOUd3eG02T1V4aUU3TVFpUEVtL3paUmViUnZISkZHQk5nclQ3WjJrL2FUUGFlRXBPZm04Q0NDVFR1TTZOamRpQ2NGTDhPcG5vODZrN3B6ZzhQSjEyTzJvdG5zcVA4MGtaVG9jampB',
		'version'  => 'd6d7b254c4b0f2ce4f7a8d322ca9a987',
		'clientId' => '97be207ba812aade6324ba7750afc001',
		'time'     => '1300861817',
		'sign'     => 'd5711102773e55743ef8dd79a2152ea1'
	)
);
*/
/*
$postFields = array(
	'username'   => 'baojunbo@gmail.com', // 用户名
	'password'   => 'you', // 密码

	'callback'   => 'parent.sinaSSOController.loginCallBack',
	'client'     => 'ssologin.js(v1.3.11)',
	'encoding'   => 'utf-8',
	'entry'      => 'blog',
	'from'       => 'referer:blog.sina.com.cn/junbobao,func:0007',
	'gateway'    => 1,
	'returntype' => 'IFRAME',
	'savestate'  => 60,
	'service'    => 'sso',
	'setdomain'  => 1,
	'useticket'  => 0
);

BFW_Curl::$includeHeader = true;
BFW_Curl::setHttpHeader('www.sina.com', 'www.google.com');
BFW_Curl::setPost($postFields);
$content = BFW_Curl::sendUrl('http://login.sina.com.cn/sso/login.php?client=ssologin.js(v1.3.11)');
echo $content . "\n";
*/
BFW_Curl::$includeHeader = true;
BFW_Curl::setHttpHeader('app19271.qzoneapp.com', 'app19271.qzoneapp.com');
$content = BFW_Curl::sendUrl('http://app19271.qzoneapp.com/share.php?1306138484');
$fp = fopen('/media/wind/ismole/a.html', 'w');
fwrite($fp, $content);
fclose($fp);
echo $content . "\n";
