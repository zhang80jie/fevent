<?php


//引入公共初始化文件 
require dirname(dirname(__FILE__)) . '/include/lib/init.php';

/**
 * 积分方面的控制器
 */
class points {

	public $result = 'result';
	private $_model;

	public function __construct() {
		if (method_exists($this, 'init')) {
			$this->init();
		}
		$this->dispatch();
	}

	protected function init() {
		$this->_model = pointsModel :: getInstace();
		isset ($_REQUEST['openid']) && $_SESSION['openid'] = $_REQUEST['openid'];
		isset ($_REQUEST['openkey']) && $_SESSION['openkey'] = $_REQUEST['openkey'];
		if (empty ($_SESSION['openid'])) {
			//	exit ('请先登录');
		}
	}

	//请求分发
	public function dispatch() {
		// print_r(QQAPIModel::get_user_info_simple($_SESSION['openid'],$_SESSION['openkey']));
		//echo $_SESSION['openid'].'<br />';   //BDF65CDFCC0520CD84BD4C4C31A0A241   14BC1E251B3BD33718298CE652E302B7
		//echo $_SESSION['openkey'].'<br />';
	//	print_r(QQAPIModel :: get_user_info_simple($_SESSION['openid'], $_SESSION['openkey']));
		//print_r(QQAPIModel::get_user_info_simple('BDF65CDFCC0520CD84BD4C4C31A0A241','14BC1E251B3BD33718298CE652E302B7'));

		//$userarray = QQAPIModel::get_user_info_simple('BDF65CDFCC0520CD84BD4C4C31A0A241','14BC1E251B3BD33718298CE652E302B7');
		$userarray = QQAPIModel :: get_user_info_simple($_SESSION['openid'], $_SESSION['openkey']);

		//echo '***'.QQAPIModel :: update_user_info($userarray,$_SESSION['openid']).'***';

		$usepointslist = $this->_model->getGroupPointDetail($_SESSION['openid']);

		$pointslist = $this->_model->getPointList();	

		if ($_REQUEST['param'] == 'usepoints') {
			if (isset ($_POST['groupid']) && isset ($_POST['points'])) {
				//	$this->result = $_POST['points'].$_POST['groupid'].'<br />';
				//$result = $_POST['points'].$_POST['groupid'].'<br />';
				// $groupres =$this->_model->getGroupsByOpenid($_SESSION['openid']);
				$r1 = $this->_model->minusPoint($_SESSION['openid'], $_POST['points']);
				$result =$r1 ;
				if($r1>0)
				   $r2 = $this->_model->addGroupPoint($_SESSION['openid'], $_POST['groupid'], $r1);
				if ($r2) {
					$result .= '使用成功';
				}else{
					$result .= '使用失败，请确定你有积分'.$r2;
				}
				include './templates/points.html';
			} else {
				$result .= "请选择要使用的群组和使用的积分数量";
				include './templates/points.html';
			}
		} else {
			include './templates/points.html';
		}

		// openid BDF65CDFCC0520CD84BD4C4C31A0A241 

	}

	public function pointsList() {
		include './templates/points.html';
	}

}

$p = new points();
?>
