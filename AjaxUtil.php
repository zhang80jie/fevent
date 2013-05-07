<?php


//引入公共初始化文件 
require dirname(dirname(__FILE__)) . '/include/lib/init.php';

/**
 * 针对Ajax部分请求的公共类
 */
class AjaxUtil {

	public $result = 'result';
	private $_model;
	private $_eventmodel ;
	private $_eventattentionmodel;
	private $_groupmodel;

	public function __construct() {
		if (method_exists($this, 'init')) {
			$this->init();
		}
		$this->dispatch();
	}

	protected function init() {
		$this->_model = pointsModel :: getInstace();
		$this->_eventmodel = eventModel :: getInstace();
		$this->_eventattentionmodel = eventattenionModel :: getInstace();
		$this->_groupmodel=groupModel:: getInstace();
		isset ($_REQUEST['openid']) && $_SESSION['openid'] = $_REQUEST['openid'];
		isset ($_REQUEST['openkey']) && $_SESSION['openkey'] = $_REQUEST['openkey'];
		if (empty ($_SESSION['openid'])) {
			//	exit ('请先登录');
		}
	}

	//请求分发
	public function dispatch() {

		if ($_REQUEST['param'] == 'group') {         //获取加入的群组
			echo json_encode($this->_model->getGroupsByOpenid($_SESSION['openid']));
		} else if ($_REQUEST['param'] == 'catalog') { //获取内容分类内容
			echo json_encode($this->_eventmodel->getEventCatalogList());
		}else if($_REQUEST['param'] == 'attention'){  //关注
			echo $this->_eventattentionmodel->addEventAttention($_REQUEST['eventid'],$_SESSION['openid']);
		}else if($_REQUEST['param'] == 'isattentioner'){ //是否是关注者
			echo $this->_eventattentionmodel->isEventAttention($_REQUEST['eventid'],$_SESSION['openid']);
		}else if($_REQUEST['param'] == 'isgroupmember'){ //是否是群组成员
			echo $this->_groupmodel->isGroupMember($_SESSION['openid'],$_REQUEST['groupid']);
		}else if($_REQUEST['param'] == 'joingroup'){ //加入群组
			echo $this->_groupmodel->joinGroup($_SESSION['openid'],$_REQUEST['groupid']);
		}

	}

}

$p = new AjaxUtil();
?>
