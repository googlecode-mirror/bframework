<?php
/**
 * BFW_Validata.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-22
 * $Id$
 */

final class BFW_Validata {
	static private $verifyCodeLength = 4;
	static private $verifySeed = '123456789abcdefghijkmnpqrstuvwxyzABCDEFGHIJKMNPQRSUVWXYZ';
	static private $times      = 100;
	static private $imgWidth   = 70;
	static private $imgHeight  = 22;
	static private $imgFont    = 13;

	static public function show($key) {
		$verifyCode = self::getRandomCode();
		setcookie($key, strtolower($verifyCode), Request::server('REQUEST_TIME') + 3600, '/');
		self::outputImg($verifyCode);
	}

	/**
	 * get random code
	 * @param interger $length
	 * @return string
	 */
	static private function getRandomCode() {
		$bgnIdx = 0;
		$endIdx = strlen(self::$verifySeed)-1;
		$code = '';
		for($i=0; $i<self::$verifyCodeLength; $i++) {
			$curPos = rand($bgnIdx, $endIdx);
			$code .= substr(self::$verifySeed, $curPos, 1);
		}
		return $code;
	}

	/**
	 * out put img
	 * @param string $verifyCode
	 */
	static private function outPutImg($verifyCode) {
		$imgFgColorArr=array(0,0,0);
		$imgBgColorArr=array(255,255,255);
		$image = imagecreatetruecolor(self::$imgWidth, self::$imgHeight);
		//用白色背景加黑色边框画个方框
		$backColor = imagecolorallocate($image, 255, 255, 255);
		$borderColor = imagecolorallocate($image, 0, 0, 0);
		imagefilledrectangle($image, 0, 0, self::$imgWidth - 1, self::$imgHeight - 1, $backColor);
		imagerectangle($image, 0, 0, self::$imgWidth - 1, self::$imgHeight - 1, $borderColor);

		$imgFgColor = imagecolorallocate ($image, $imgFgColorArr[0], $imgFgColorArr[1], $imgFgColorArr[2]);
		self::drawStr($image, $verifyCode, $imgFgColor);
		self::pollute($image);

		// header('Content-type: image/png');
		imagepng($image);
		imagedestroy($image);
	}

	/**
	 * draw str
	 * @param mixed $image
	 * @param string $verifyCode
	 * @param mixed $imgFgColor
	 */
	static private function drawStr($image, $verifyCode, $imgFgColor) {
		$imgWidth = imagesx($image);
		$imgHeight = imagesy($image);

		$count = strlen($verifyCode);
		$xpace = ($imgWidth/$count);

		$x = ($xpace-6)/2;
		$y = ($imgHeight/2-8);
		for ($p = 0; $p<$count;  $p ++) {
			$xoff = rand(-2, +2);
			$yoff = rand(-2, +2);
			$curChar = substr($verifyCode, $p, 1);
			$color = imagecolorallocate($image, rand(0,255), rand(0,255), rand(0,255));
			imagestring($image, self::$imgFont, $x+$xoff, $y+$yoff, $curChar, $color);
			$x += $xpace;
		}
		return 0;
	}

	/**
	 * pollute
	 */
	static private function pollute($image) {
		$imgWidth = imagesx($image);
		$imgHeight = imagesy($image);
		for($j=0; $j<self::$times; $j++) {
			$x = rand(0, $imgWidth);
			$y = rand(0, $imgHeight);

			$color = imagecolorallocate($image, rand(0,255), rand(0,255), rand(0,255));
			imagesetpixel($image, $x, $y, $color);
		}
	}
}
?>
