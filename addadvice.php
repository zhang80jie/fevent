<?php

//引入公共初始化文件 
require dirname(dirname(__FILE__)) . '/include/lib/init.php';

class addAdvice{
	private $_model;

	public function __construct() {
		if (method_exists($this, 'init')) {
			$this->init();
		}
		$this->dispatch();
	}

	protected function init() {
		$this->_model = adviceModel :: getInstace();
		isset ($_REQUEST['openid']) && $_SESSION['openid'] = $_REQUEST['openid'];
		isset ($_REQUEST['openkey']) && $_SESSION['openkey'] = $_REQUEST['openkey'];
//		if (empty ($_SESSION['openid'])) {
//			//	exit ('请先登录');
//		}
	}

	public function dispatch() {
		
		if (empty($_SESSION['openid'])){
			$result = $_SESSION['openid']."你还未登陆,请登录后发布";
		} else
			if (isset ($_POST["submit"])) {
			
			$adviceid =$_POST['name'];
			$advice_detail =$_POST['advice_detail'];
		
		if($adviceid==0){
			$result = '请选择计划';
		}else{
		$sqlresult = $this->_model->addAdvice($_SESSION['openid'],$adviceid,$advice_detail);
		
		if ($sqlresult) {
			$result = '增加成功,谢谢你的建议';
		}else{
			$result = '增加失败';
		}
		}

	}
		include './templates/addadvice.html';
	}
}
$p = new addAdvice();
?>
