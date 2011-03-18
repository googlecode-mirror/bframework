<?php
/**
 * BFW_View.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-21
 * $Id$
 */
final class BFW_View {
	static public  $compileCheck   = true;
	static public  $debugging      = false;
	static public  $templateDir    = null;
	static public  $compileDir     = null;
	static public  $cacheDir       = null;
	static public  $leftDelimiter  = '<%';
	static public  $rightDelimiter = '%>';
	static public  $cache          = false;
	static public  $cacheLifetime  = 1800;

	static private $view;
	static private $data = array();

	/**
	 * 启用Smarty缓存
	 */
	static public function setCache($cacheLifetime = null) {
		self::$cache = true;
		if ($cacheLifetime) {
			self::$cacheLifetime = $cacheLifetime;
		}
	}

	/**
	 * 设置模板内需要被替换的变量
	 * @param string $key
	 * @param mixed  $val
	 */
	static public function set($key, $val = null) {
		if ($val) {
			self::$data[$key] = $val;
		} else {
			if (is_array($key)) {
				foreach ($key as $k => $v) {
					self::set($k, $v);
				}
			}
		}
	}

	/**
	 * 显示模板/返回结果
	 * @param string $template
	 * @param string $type
	 * @param mixed  $cacheId
	 * @param mixed  $compileId
	 */
	static public function display($template, $type = 'display', $cacheId = null, $compileId = null) {
		self::smarty();
		if (!self::$view->is_cached($template)) {
			foreach (self::$data as $key => $value) {
				self::$view->assign($key, $value);
			}
		}
		if ($type === 'display') {
			self::$view->display($template, $cacheId, $compileId);
		} else {
			return self::$view->fetch($template, $cacheId, $compileId);
		}
	}

	/**
	 * 实例化Smarty
	 */
	static private function smarty() {
		if (!is_object(self::$view)) {
			require_once 'smarty/Smarty.class.php';
			self::$view = new Smarty;
			self::$view->compile_check  = self::$compileDir;
			self::$view->debugging      = self::$debugging;
			self::$view->template_dir   = self::$templateDir ? self::$templateDir : TPL_PATH . '/templates';
			self::$view->compile_dir    = self::$compileDir ? self::$compileDir : TPL_PATH . '/templates_c';
			self::$view->left_delimiter = self::$leftDelimiter;
			self::$view->right_delimiter= self::$rightDelimiter;
			if (self::$cache) {
				self::$view->caching        = true;
				self::$view->cache_dir      = self::$cacheDir ? self::$cacheDir : TPL_PATH . '/cache';
				self::$view->cache_lifetime = self::$cacheLifetime;
			}
		}
		return self::$view;
	}
}
