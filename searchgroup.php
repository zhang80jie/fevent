<?php
//引入公共初始化文件 
require dirname(dirname(__FILE__)) . '/include/lib/init.php';


class searchgroup{
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
		$groupHotList = $this->_model->getHotGroupList('12');  //或者热门群组列表
		$groupNewCreatedList = $this->_model->getGroupListCreated('12');
		
		if (empty($_SESSION['openid'])){
			$result = "你还未登陆,请登录后发布";
		} else if (isset ($_POST["submit"])) {
			$selectby =$_POST['selectby'];
			$keyword =$_POST['keyword'];
		
			if(strlen(trim($keyword))==0){
				$result= '请输入查询关键字';
			}else{
				if($selectby==0){
					$searchResultList =  $this->_model->getGroupListByGroupName($keyword,9);
				}else{
					$searchResultList =  $this->_model->getGroupListByGroupId($keyword,9);
				}
		}			
	  }
			
	include './templates/searchgroup.html';
    }
}
$p = new searchgroup();
?>
