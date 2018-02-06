<?php
/**
 * 
 * Empty (空模块)
 *
 * @package      	CHENGMING
 * @author          chengming QQ:317205134 <admin@9chengming.com>
 * @copyright     	Copyright (c) 2008-2014  (http://www.9chengming.com)
 * @license         http://www.9chengming.com/license.txt
 * @version        	CHENGMING企业网站管理系统 v5.1 2014-06-20 9chengming.com $
 */
if(!defined("Chengming")) exit("Access Denied");
class EmptyAction extends Action
{	
	public function _empty()
	{
		R('Admin/Content/'.ACTION_NAME);
	}
}
?>