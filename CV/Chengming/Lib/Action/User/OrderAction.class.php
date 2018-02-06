<?php
/**
 * 
 * OrderAction.class.php (订单管理)
 *
 * @package      	YOURPHP
 * @author          liuxun QQ:147613338 <admin@yourphp.cn>
 * @copyright     	Copyright (c) 2008-2011  (http://www.yourphp.cn)
 * @license         http://www.yourphp.cn/license.txt
 * @version        	YourPHP企业网站管理系统 v2.1 2011-03-01 yourphp.cn $
 */
if(!defined("Chengming")) exit("Access Denied");
class OrderAction extends BaseAction
{

	function _initialize()
    {	
		parent::_initialize();
		$this->dao = M('Order');
		$this->assign('bcid',0);
		$user =  M('User')->find($this->_userid);
		$this->assign('vo',$user);
    }

    public function index()
    {

		if($_REQUEST['sn']){
		$sn = get_safe_replace($_REQUEST['sn']);
		unset($_REQUEST['sn']);
		}
		//import('@.Action.Adminbase');
		//$c=new AdminbaseAction();
		if($this->_userid || $sn){ 
			$map['userid']=intval($this->_userid);
			if($sn)$map['sn'] = $sn;
			$data=$this->_list(MODULE_NAME,$map);
		}
		$this->assign ( 'page', $data['page'] );
		$this->assign ( 'list', $data['list'] );
        $this->display();
    }

	public function show()
    {
		$sn = intval($_REQUEST['sn']);
		$id= intval($_REQUEST['id']);
		$order = $id ? $this->dao->find($id) : $this->dao->getBySn($sn) ;
		if(!$order && $order['userid']!=$this->_userid) $this->success (L('do_empty'));

		$order_data = M('Order_data')->where("order_id='{$order[id]}'")->select();
		$amount=0;
		foreach($order_data as $key=>$r){
			$amount = $amount+$r['price'];
		}
	 

		$Payment = M('Payment')->find($order['pay_id']);
		$Shipping = M('Shipping')->find($shippingid);
		$Area = M('Area')->getField('id,name');
		$this->assign('Area',$Area);
		$this->assign('Payment',$Payment);
		$this->assign('Shipping',$Shipping);


		if($order['pay_code'] && $order['status']<2 && $order['pay_status']<2){
			
			$aliapy_config = unserialize($Payment['pay_config']);
			$aliapy_config['order_sn']= $order['sn'];
			$aliapy_config['order_amount']= $order['order_amount'];
			$aliapy_config['body'] = $order['consignee'].' '.$order['postmessage'];
			import("@.Pay.".$order['pay_code']);
			$pay=new $order['pay_code']($aliapy_config);
			$paybutton = $pay->get_code();
			$this->assign('paybutton',$paybutton);
		}
		$this->assign('order',$order);
		$this->assign('order_data',$order_data);
		$this->assign('amount',$amount); 
		$this->display();
    }



	function ajax(){
		
		$model= M('Order');
		$id=intval($_POST['id']);
		$order = $model->find($id);
		if($order['userid']!=$this->_userid)die(json_encode(array('msg'=>L('do_empty'))));
		$_POST = get_safe_replace($_POST);
		if($_GET['do']=='saveaddress'){
			$r = $model->save($_POST);
			die(json_encode(array('id'=>1)));
		}elseif($_GET['do'] =='order_status'){
			$_POST['status']=3;
			$_POST['confirm_time']=time();
			$r = $model->save($_POST);
			die(json_encode(array('id'=>1)));
		}elseif($_GET['do'] =='pay_status'){
			$_POST['pay_status']=3;
			$r = $model->save($_POST);
			die(json_encode(array('id'=>1)));
		}elseif($_GET['do'] =='shipping_status'){
			$_POST['shipping_status']=$_POST['num'];
			unset($_POST['num']);
			$_POST['accept_time']= $_POST['shipping_status']==2 ? time() : '';
			$r = $model->save($_POST);
			die(json_encode(array('id'=>1)));
		}
	}


	function _list($modelname, $map = '', $sortBy = '', $asc = false ,$listRows = 15) {
		$model = M($modelname);
		$id=$model->getPk ();
		$this->assign ( 'pkid', $id );
		
		if (isset ( $_REQUEST ['order'] )) {
			$order = $_REQUEST ['order'];
		} else {
			$order = ! empty ( $sortBy ) ? $sortBy : $id;
		}
		if (isset ( $_REQUEST ['sort'])) {
			$_REQUEST ['sort']=='asc' ? $sort = 'asc' : $sort = 'desc';
		} else {
			$sort = $asc ? 'asc' : 'desc';
		}


		$_REQUEST ['sort'] = $sort;
		$_REQUEST ['order'] = $order;

		$keyword=$_REQUEST['keyword'];
		$searchtype=$_REQUEST['searchtype'];
		$groupid =intval($_REQUEST['groupid']);
		$catid =intval($_REQUEST['catid']);
		$posid =intval($_REQUEST['posid']);
		$typeid =intval($_REQUEST['typeid']);

		if(APP_LANG)if($this->moduleid)$map['lang']=array('eq',LANG_ID);


		if(!empty($keyword) && !empty($searchtype)){
			$map[$searchtype]=array('like','%'.$keyword.'%');
		}
		if($groupid)$map['groupid']=$groupid;
		if($catid)$map['catid']=$catid;
		if($posid)$map['posid']=$posid;
		if($typeid) $map['typeid']=$typeid;

		$tables = $model->getDbFields();
 

		foreach($_REQUEST['map'] as $key=>$res){
				if(  ($res==='0' || $res>0) || !empty($res) ){					 
					if($_REQUEST['maptype'][$key]){
						$map[$key]=array($_REQUEST['maptype'][$key],$res);
					}else{
						$map[$key]=intval($res);
					}
					$_REQUEST[$key]=$res;
				}else{					
					unset($_REQUEST[$key]);
				}
		}
 

		$this->assign($_REQUEST);

		//取得满足条件的记录总数
		$count = $model->where ( $map )->count ( $id );
		if ($count > 0) {
			import ( "@.ORG.Page" );
			//创建分页对象
			if (! empty ( $_REQUEST ['listRows'] )) {
				$listRows = $_REQUEST ['listRows'];
			}
			$page = new Page ( $count, $listRows );
			//分页查询数据

			$field=$this->module[$this->moduleid]['listfields'];
			$field= (empty($field) || $field=='*') ? '*' : 'id,catid,url,posid,title,thumb,title_style,userid,username,hits,createtime,updatetime,status,listorder' ;
			$voList = $model->field($field)->where($map)->order( "`" . $order . "` " . $sort)->limit($page->firstRow . ',' . $page->listRows)->select ( );
			
			//分页跳转的时候保证查询条件
			foreach ( $map as $key => $val ) {
				if (! is_array ( $val )) {
					$page->parameter .= "$key=" . urlencode ( $val ) . "&";
				}
			}

			$map[C('VAR_PAGE')]='{$page}';
			unset($map['lang']);
			$map['lang']=LANG_ID;
			$page->urlrule = U($modelname.'/index', $map);
			//分页显示
			$page = $page->show ();
			//列表排序显示
			$sortImg = $sort; //排序图标
			$sortAlt = $sort == 'desc' ? '升序排列' : '倒序排列'; //排序提示
			$sort = $sort == 'desc' ? 1 : 0; //排序方式
			//模板赋值显示
			$data['list']=$voList;
			$data['page']= $page;
			//print_r($data);
			//$this->assign ( 'list', $voList );
			//$this->assign ( 'page', $page );
		}
		return $data;
	}

	
}
?>