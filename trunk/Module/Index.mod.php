<?php
/**
 * Index.mod.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-20
 * $Id$
 */
final class Index {
	// public function __construct() {}

	public function show() {
		BFW_Obj::set('User')->getInfo('baojunbo');
		BFW_View::display('index.htm');
	}
}
