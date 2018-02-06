<?php
/**
 *
 * Login(后台登陆记录)
 *
 * @package      	CHENGMING
 * @author          chengming QQ:317205134 <admin@9chengming.com>
 * @copyright     	Copyright (c) 2008-2014  (http://www.9chengming.com)
 * @license         http://www.9chengming.com/license.txt
 * @version        	CHENGMING企业网站管理系统 v5.1 2014-06-20 9chengming.com $
 */
if(!defined("Chengming")) exit("Access Denied");
class LogAction extends  AdminbaseAction {
    function _initialize()
    {	
		parent::_initialize();
    }
	function delete(){
		$day=intval($_GET['day']);
		if($day==1){
			$time = time()-60*60*24*30;
		}elseif($day==2){
			$time =  time()-60*60*24*90;
		}else{
			$this->error (L('do_empty'));
		}

		M(MODULE_NAME)->where("time < $time")->delete();
		$this->success(L('delete_ok'));

	}
 
}
