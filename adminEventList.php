<?php

//引入公共初始化文件 
require dirname(dirname(__FILE__)) . '/include/lib/init.php';

class adminEventList{
	private $_model;

	public function __construct() {
		if (method_exists($this, 'init')) {
			$this->init();
		}
		$this->dispatch();
	}

	protected function init() {
		$this->_model = adminEventModel :: getInstace();
		
	}

	public function dispatch() {
		 
		if (empty($_SESSION['username'])){
			$result ="你还未登陆,请登录后发布";
			include './templates/adminuserlogin.html';
		}else{
			$command=$_REQUEST['command'];
			$event_id=$_REQUEST['event_id'];
			if(($command==1)||($command==-1)){
				$result=$this->_model->updateAdminEvent($event_id,$command);	
				if($result==1){
					$result='审核成功';
				}else{
					$result='审核失败请重新审核';
				}			
			}
			
			$eventlist= $this->_model->getAdminEventList();
			include './templates/admineventlist.html';
		}
	
	}
}
$p = new adminEventList();
?>
