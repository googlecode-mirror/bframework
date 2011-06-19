<?php
/**
 * BFW_Pager.class.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-22
 * $Id$
 */
final class BFW_Pager extends BFW_DB {
	static private $totalRows = 0;
	static private $perPage   = 10;
	static private $group     = 10;
	static private $callJs    = '';

	static public function page($sql, $params, $pageParam) {
		self::_setParams($pageParam);
		self::_getRows($sql, $params);
		if (0 == self::$totalRows) {
			return array(
				'totalRows' => 0,
				'currentPage' => 0,
				'totalPage' => 0,
				'data' => array(),
				'links' => ''
			);
		}
		$page  = self::_page(self::$totalRows, self::$perPage, self::$group);
		$data  = self::_getData($sql, $page['start'], self::$perPage, $params);
		$links = self::_getLink($page['groupId'], $page['pageId'], $page['totalPage'], $page['totalGroup']);
		$res = array(
			'data' => $data,
			'links' => $links,
			'currentPage' => $page['pageId'],
			'totalPage' => $page['totalPage'],
			'totalRows' => self::$totalRows
		);
		return $res;
	}

	static private function _setParams($params) {
		if (isset($params['perPage'])) self::$perPage = $params['perPage'];
		if (isset($params['group'])) self::$group = $params['group'];
		if (isset($params['callJs'])) self::$callJs = $params['callJs'];
	}

	static private function _page($totalRows, $perPage, $group) {
		$totalPage  = ceil($totalRows / $perPage);
		$totalGroup = ceil($totalPage / $group);
		$pageId  = BFW_Request::get('pageId');
		$groupId = BFW_Request::get('groupId');
		if (0 == $pageId) $pageId = 1;
		if (0 == $groupId) {
			$groupId = ceil($pageId / $group);
		} else {
			$pageId = ($groupId - 1) * $group + 1;
		}
		$limit = ($pageId - 1) * $perPage;
		$start = $limit;
   		// if ($end >= self::$totalRows) $end = self::$totalRows;
   		// $end = self::$totalRows;
   		$page['limit']     = $limit;
   		$page['start']     = $start;
   		$page['pageId']    = $pageId;
   		$page['groupId']   = $groupId;
   		$page['totalPage'] = $totalPage;
   		$page['totalGroup']= $totalGroup;
		return $page;
	}

	static private function _getRows($sql, $sqlParams) {
		if ($num = strpos(strtoupper($sql), 'ORDER')) {
			$countSql = substr($sql, 0, $num);
		} else {
			$countSql = $sql;
		}
		$countSql = 'SELECT COUNT(*) ' . stristr($countSql, 'FROM');
		self::$totalRows = parent::getOne_($countSql, $sqlParams, 0);
	}

	static private function _getLink($groupId, $pageId, $totalPage, $totalGroup) {
		$language  = 'language_' . BFW_Config::get('site.language');
		$urlGleft  = '';
		$condition = BFW_Request::server('QUERY_STRING') ? '&' . BFW_Request::server('QUERY_STRING') : '';
		$condition = preg_replace('/&?pageId=[0-9]*/', '', $condition);
		$condition = preg_replace('/&?groupId=[0-9]*/', '', $condition);
		$minpage   = ($groupId - 1) * self::$group + 1;
		$maxpage   = $minpage + self::$group - 1;
		$fileName = BFW_Request::server('PHP_SELF');
		if ($maxpage > $totalPage){
			$maxpage = $totalPage;
		}
		if ($groupId == 1){
			$urlPleft = '';
		} else {
			$group1 = $groupId - 1;
			if (self::$callJs) {
				$gLeftHref = 'href="#" onclick="' . self::$callJs . '(\'groupId=' . $group1 . $condition . '\')"';
			} else {
				$gLeftHref = 'href="' . $fileName . '?groupId=' . $group1 . $condition . '"';
			}
			$urlGleft = '<a ' . $gLeftHref . ' title="' . BFW_Config::get($language . '.page.up') . self::$group . BFW_Config::get($language . '.page.page') . '">&lt;&lt;</a>';
		}
		if ($pageId == 1){
			$urlPleft = '';
		} else {
			$page1 = $pageId - 1;
			if (self::$callJs) {
				$pLeftHref = 'href="#" onclick="' . self::$callJs . '(\'pageId=' . $page1 . $condition . '\')"';
			} else {
				$pLeftHref = 'href="' . $fileName . '?pageId=' . $page1 . $condition . '"';
			}
			$urlPleft = '<a ' . $pLeftHref . ' title="' . BFW_Config::get($language . '.page.prev') . '">&lt;</a>';
		}
		if ($pageId >= $totalPage){
			$urlPright = '';
		} else{
			$page2 = $pageId + 1;
			if (self::$callJs) {
				$pRightHref = 'href="#" onclick="' . self::$callJs . '(\'pageId=' . $page2 . $condition . '\')"';
			} else {
				$pRightHref = 'href="' . $fileName . '?pageId=' . $page2 . $condition . '"';
			}
			$urlPright = '<a ' . $pRightHref . ' title="' . BFW_Config::get($language . '.page.next') . '">&gt;</a>';
		}
		if ($groupId >= $totalGroup){
			$urlGright = '';
		} else {
			$group2 = $groupId + 1;
			if (self::$callJs) {
				$gRightHref = 'href="#" onclick="' . self::$callJs . '(\'groupId=' . $group2 . $condition . '\')"';
			} else {
				$gRightHref = 'href="' . $fileName . '?groupId=' . $group2 . $condition . '"';
			}
			$urlGright = '<a ' . $gRightHref . ' title="' . BFW_Config::get($language . '.page.down') . self::$group . BFW_Config::get($language . '.page.page') . '">&gt;&gt;</a>';
		}
		$sumi = '<div class="page">';
		$sumi .= $urlGleft . $urlPleft;
		for ($i = $minpage; $i <= $maxpage; $i ++){
			if ($i == $pageId){
				$sumi .= '<span class="currentPage">' . $i . '</span>';
		 	} else {
				if (self::$callJs) {
					$pageHref = 'href="#" onclick="' . self::$callJs . '(\'pageId=' . $i . $condition . '\')"';
				} else {
					$pageHref = 'href="' . $fileName . '?pageId=' . $i . $condition . '"';
				}
				$sumi .= '<a ' . $pageHref . '>' . $i . '</a>';
			}
		}
		$sumi .= $urlPright . $urlGright;
		$sumi .= '</div>';
		//if ($maxpage == 1) $sumi = '';
		return $sumi;
	}

	static private function _getData($sql, $start, $end, $sqlParams) {
		$data = parent::getAll_($sql . ' LIMIT ' . $start . ', ' . $end, $sqlParams);
		return $data;
	}
}
