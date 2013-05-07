<?php
//引入公共初始化文件 
require dirname(dirname(__FILE__)) . '/include/lib/init.php';


class addgroup{
	private $_model;

	public function __construct() {
		if (method_exists($this, 'init')) {
			$this->init();
		}
		$this->dispatch();
	}

	protected function init() {
		$this->_model = groupModel :: getInstace();
		isset ($_REQUEST['openid']) && $_SESSION['openid'] = $_REQUEST['openid'];
		isset ($_REQUEST['openkey']) && $_SESSION['openkey'] = $_REQUEST['openkey'];
//		if (empty ($_SESSION['openid'])) {
//			//	exit ('请先登录');
//		}
	}
	
	public function dispatch() {
		$addedGroupList = $this->_model->getGroupListCreatedByOpenid($_SESSION['openid']);
		if (empty($_SESSION['openid'])){
			$result = $_SESSION['openid']."你还未登陆,请登录后发布";
		} else if (isset ($_POST["submit"])) {
			$groupname =$_POST['groupname'];
			$groupdesc =$_POST['groupdesc'];
			$qqgroupnumber =$_POST['qqgroupnumber'];
		
		if((strlen(trim($groupname))==0)||(strlen(trim($groupdesc))==0)){
			$result= '请输入完整的群组名和群组描述';
		}else{
			$groupnamevalidate = QQAPIModel :: validateword_simple($_SESSION['openid'], $_SESSION['openkey'], $groupname);
			$groupdescvalidate = QQAPIModel :: validateword_simple($_SESSION['openid'], $_SESSION['openkey'], $groupdesc);
		if ($groupnamevalidate['is_dirty'] == 1) {
				$result= '你输入的群组名有非法字符:' . $groupnamevalidate['msg'];
			} else if ($groupdescvalidate['is_dirty'] == 1) {
					echo '你输入的群组简介有非法字符:' . $groupdescvalidate['msg'];
		    } else {
					$sqlResult = $this->_model->addGroup($groupname,$groupdesc,$qqgroupnumber,$_SESSION['openid']);
					if ($sqlResult) {
						$result= '增加成功';
					}else{
						$result= '增加失败';
					}
			}	
		
					
		}			
	  }
			
		include './templates/addgroup.html';
      }
}
$p = new addgroup();
?>
