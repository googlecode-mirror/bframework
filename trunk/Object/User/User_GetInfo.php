<?php
/**
 * User_GetInfo.php
 * @author Junbo Bao <baojunbo@gmail.com>
 * @create date: 2011-01-21
 * $Id$
 */
final class User_GetInfo extends User {
	public function getInfo($name) {
		$uName = BFW_Dao::set('user')->getOne(1, 'ui_name');
		BFW_View::set('uName', $uName);
		$user = BFW_Dao::set('user_1')->getRow(array('u_name' => $name), '*', array(array('table' => 'test_1.user_info', 'alias' => 'ui', 'condition' => 'USING(u_id)')));
		BFW_View::set('user', $user);
		$users= BFW_Dao::set('user_1')->getAll(array('sql' => 'u_id < :uId', 'par' => array(':uId' => 11)));
		BFW_View::set('users', $users);
		$pageUsers = BFW_Dao::set('user_1')->getPageAll(array('sql' => 'u_id < :uId', 'par' => array(':uId' => 100)));
		BFW_View::set($pageUsers);
		$userObj1 = BFW_Dao::set('user_1')->load(1);
		$userObj2 = BFW_Dao::set('user_1')->load(array('u_id' => 1, 'u_name' => $name));
		$userObj1->setData('u_name', 'baojunbo');
		$rs = $userObj1->update();
		// print $rs . "<br />";
		$userObj2->setData('u_name', 'junbo bao');
		$userDao = BFW_Dao::set('user');
		$userDao->setData('ui_name', 'baojunbo');
		$userDao->setData('ui_email', 'baojunbo@gmail.com');
		$rs = $userDao->update(1);
		// print $rs . "<br />";
		// $userDao->delete(1);
		// $rs = $userDao->insert(array('key' => array('ui_name', 'ui_email'), 'value' => array(array('baojunbo', 'baojunb@ismole.com'), array('junbo', 'junbo@hotmail.com'))));
		// $rs = $userDao->insert(array('key' => array('ui_name', 'ui_email'), 'value' => array('baojunbo', 'baojunb@ismole.com')));
		// print $rs . "\n";
	}
}
