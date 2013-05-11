<?php

//引入公共初始化文件 
require dirname(dirname(__FILE__)) . '/include/lib/init.php';

class adminLogin{
	private $_model;

	public function __construct() {
		if (method_exists($this, 'init')) {
			$this->init();
		}
		$this->dispatch();
	}

	protected function init() {
		$this->_model = adminUserModel :: getInstace();
		
	}

	public function dispatch() {
		
	if (isset ($_POST["submit"])) {
		$username =$_POST['username'];
		$passsword =$_POST['passsword'];
		
		if(empty($username)||empty($passsword)){
			$result = '请输入账号和密码';
		}else{
			$isadmin = $this->_model->isAdminUser($username,$passsword);
			if ($isadmin==0) {
				$result = '管理员账号密码错误，请确认后输入';
			}else{
				$_SESSION['username'] = $username;
				$_SESSION['isadmin']=1;
				$result = '登录成功<a href="adminEventList.php"><font color="red">管理页面</font></a>';
			}
		}

	}
		include './templates/adminuserlogin.html';
	}
}
$p = new adminLogin();
?>
