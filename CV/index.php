<?php
/**
 *
 * index(入口文件)
 *
 * @package      	CHENGMING
 * @author          chengming QQ:317205134 <admin@9chengming.com>
 * @copyright     	Copyright (c) 2008-2014  (http://www.9chengming.com)
 * @license         http://www.9chengming.com/license.txt
 * @version        	CHENGMING企业网站管理系统 v5.1 2014-06-20 9chengming.com $
 */
header("Content-type: text/html;charset=utf-8");
if(!is_file('./Cache/config.php')){
	echo "找不到配置文件";
	exit;
}
ini_set('memory_limit','32M');
error_reporting(E_ERROR | E_WARNING | E_PARSE);
define('Chengming',true);
define('UPLOAD_PATH','./Uploads/');
define('VERSION','v5');
define('UPDATETIME','20121225');
define('APP_NAME','Chengming');
define('APP_PATH','./Chengming/');
define('APP_LANG',true);
define('APP_DEBUG',false);
define('THINK_PATH','./Core/');
require(THINK_PATH.'Core.php');
?>