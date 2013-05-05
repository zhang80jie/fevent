<?php

//引入公共初始化文件 
require dirname(dirname(__FILE__)) . '/include/lib/init.php';

class addeventnew {
	private $_model;

	public function __construct() {
		if (method_exists($this, 'init')) {
			$this->init();
		}
		$this->dispatch();
	}

	protected function init() {
		$this->_model = eventModel :: getInstace();
		isset ($_REQUEST['openid']) && $_SESSION['openid'] = $_REQUEST['openid'];
		isset ($_REQUEST['openkey']) && $_SESSION['openkey'] = $_REQUEST['openkey'];
//		if (empty ($_SESSION['openid'])) {
//			//	exit ('请先登录');
//		}
	}

	public function dispatch() {
		$addedEventList = $this->_model->getAddedEventList();
		if (empty($_SESSION['openid'])){
			$result = $_SESSION['openid']."你还未登陆,请登录后发布";
		} else
			if (isset ($_POST["submit"])) {
				$event_name = $_POST['event_name'];
				$event_date = $_POST['event_date'];
				$arrange = $_POST['arrange'];
				$groupid = $_POST['groupid'];
				$event_detail = $_POST['event_detail'];
				$content_catalog =  $_POST['content_catalog'];
				
				$eventvalidate = QQAPIModel :: validateword_simple($_SESSION['openid'], $_SESSION['openkey'], $event_name);
				$eventdetailvalidate = QQAPIModel :: validateword_simple($_SESSION['openid'], $_SESSION['openkey'], $event_detail);
				if ((strlen(trim($event_name)) == 0) || (strlen(trim($event_date)) == 0)) {
					$result = '请输入事件名称和开始时间';
				} else {

					if (($eventvalidate['is_dirty'] == 1)) {
						$result = '你输入的事件名称有非法字符:' . $eventvalidate['msg'];
					} else
						if ($eventdetailvalidate['is_dirty'] == 1) {
							$result = '你输入的事件描述有非法字符:' . $eventdetailvalidate['msg'];
						} else {
							$sqlResult = $this->_model->addEvent($_SESSION['openid'], $event_name, $event_date, $arrange, $groupid, $event_detail,$content_catalog);
							if ($sqlResult) {
								$result = '增加成功,如果你发布的范围是公开/群组,请耐心等待我们的审核&nbsp;<a href="indexlist.php?arrange=1"><font color="red">我的大事件</font></a>&nbsp;&nbsp;<a href="addevent.php"><font color="red">继续新增</font></a>';
								 $this->_model->updateCatalogUsed($content_catalog); //更新类别使用状况
							} else {
								$result = '增加失败，请稍微等下重新发布';
							}
						}
				}

			}
		include './templates/addevent.html';
	}
}
$p = new addeventnew();
?>
