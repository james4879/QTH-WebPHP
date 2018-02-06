<?php
/**
 * 
 * Role(会员组管理)
 * * @package      	zomsky
 * @author          CHENGMING QQ:317205134 <admin@9chengming.com>
 * @copyright     	Copyright (c) 2008-2014  (http://www.9chengming.com)
 * @license         http://www.9chengming.com/license.txt
 * @version        	CHENGMING企业网站管理系统 v5.1 2014-06-20 9chengming.com $
 */
if(!defined("Chengming")) exit("Access Denied");
class RoleAction extends AdminbaseAction {

	protected $dao;
    function _initialize()
    {	
		parent::_initialize();		
	
    }

	public function _before_insert()
    {
		$_POST['allowpost'] = $_POST['allowpost'] ? 1 : 0 ;
		$_POST['allowpostverify'] = $_POST['allowpostverify'] ? 1 : 0 ;
		$_POST['allowupgrade'] = $_POST['allowupgrade'] ? 1 : 0 ;
		$_POST['allowsendmessage'] = $_POST['allowsendmessage'] ? 1 : 0 ;
		$_POST['allowattachment'] = $_POST['allowattachment'] ? 1 : 0 ;
		$_POST['allowsearch'] = $_POST['allowsearch'] ? 1 : 0 ;
	}


	public function _before_update()
    {
		$_POST['allowpost'] = $_POST['allowpost'] ? 1 : 0 ;
		$_POST['allowpostverify'] = $_POST['allowpostverify'] ? 1 : 0 ;
		$_POST['allowupgrade'] = $_POST['allowupgrade'] ? 1 : 0 ;
		$_POST['allowsendmessage'] = $_POST['allowsendmessage'] ? 1 : 0 ;
		$_POST['allowattachment'] = $_POST['allowattachment'] ? 1 : 0 ;
		$_POST['allowsearch'] = $_POST['allowsearch'] ? 1 : 0 ;
	}

}
?>