<?php
/**
 * 
 * Theme(风格选择)
 * * @package      	zomsky
 * @author          CHENGMING QQ:317205134 <admin@9chengming.com>
 * @copyright     	Copyright (c) 2008-2014  (http://www.9chengming.com)
 * @license         http://www.9chengming.com/license.txt
 * @version        	CHENGMING企业网站管理系统 v5.1 2014-06-20 9chengming.com $
 */
 if(!defined("Chengming")) exit("Access Denied");
class ThemeAction extends AdminbaseAction
{
	 protected $filepath,$publicpath;
    function _initialize()
    {	
		parent::_initialize();
		$this->filepath = TMPL_PATH.'Home/'.$this->sysConfig['DEFAULT_THEME'].'/'; 
		$this->publicpath = TMPL_PATH.'Home/'.$this->sysConfig['DEFAULT_THEME'].'/Public/';
		$this->tplpath = TMPL_PATH.'Home/';
    }

    public function index()
    {
		$filed = glob($this->tplpath.'*');
		foreach ($filed as $key=>$v) {
			$arr[$key]['name'] =  basename($v);
			if(is_file($this->tplpath.$arr[$key]['name'].'/preview.jpg')){
				$arr[$key]['preview']=$this->tplpath.$arr[$key]['name'].'/preview.jpg';
			}else{
				$arr[$key]['preview']=__ROOT__.'/Public/Images/nopic.jpg';
			}
			if($this->sysConfig['DEFAULT_THEME']==$arr[$key]['name']) $arr[$key]['use']=1;
		}

		$this->assign ( 'themes',$arr );
        $this->display ();
    }
	public function chose()
	{
		$theme = $_GET['theme'];
		if($theme){
			$Model = M('Config');
			$r = $Model->where("varname='DEFAULT_THEME'")->setField('value',$theme);
			savecache('Config');
			$this->success(L('do_ok'));
		}else{
			$this->error(L('do_empty'));
		}
	}
	public function upload()
	{
		$this->display ();
	}
}
?>