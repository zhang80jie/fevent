<?php

//引入公共初始化文件 
require dirname(dirname(__FILE__)) . '/include/lib/init.php';



/**
 * 首页的控制器
 */
class indexlist {

	public $result = 'result';
	private $_model;
	private $_groupmodel;
	
	public function __construct() {
		if (method_exists($this, 'init')) {
			$this->init();
		}
		$this->dispatch();
	}

	protected function init() {
		 
		if (empty ($_SESSION['openid'])) {
			isset ($_REQUEST['openid']) && $_SESSION['openid'] = $_REQUEST['openid'];
			isset ($_REQUEST['openkey']) && $_SESSION['openkey'] = $_REQUEST['openkey'];
		}
		$this->_model = indexlistModel :: getInstace();
		$this->_groupmodel=groupModel:: getInstace();
	}

	//请求分发
	public function dispatch() {
		$page= isset($_GET['page'])?$_GET['page']:1;//默认页码
		$eventsList = '' ;  //显示的查询结果
		$phpfile ="indexlist.php?1=1";
		//$result=$_REQUEST['openid'].'***'.$_SESSION['openid'];
		if ($_REQUEST['arrange'] == '1') {
			$sql ="select f.event_id,f.event_desc,f.event_happened_date,f.attention_count ,f.event_detail from fevent_eventcontent  f where date_format(f.event_happened_date,'%Y%m%d') > date_format((curdate() - interval 3 day),'%Y%m%d') and f.event_createdby_openid='".$_SESSION['openid']."' and  f.event_open_arrage=0 order by f.attention_count desc" ;
			$phpfile=$phpfile.'&arrange=1';
		} else if ($_REQUEST['arrange'] == '2'){
			$sql ="select f.event_id,f.event_desc,f.event_happened_date,f.attention_count ,f.event_detail from fevent_eventcontent  f where date_format(f.event_happened_date,'%Y%m%d') > date_format((curdate() - interval 3 day),'%Y%m%d') and f.event_open_arrage in (select fg.group_id from fevent_user_group fg where fg.openid='".$_SESSION['openid']."')   and f.event_isprove=1  order by f.attention_count desc ";
			$phpfile=$phpfile.'&arrange=2';
		} else if($_REQUEST['arrange'] == '3'){
			$sql = "select f.event_id,f.event_desc,f.event_happened_date,f.attention_count ,f.event_detail from fevent_eventcontent  f where date_format(f.event_happened_date,'%Y%m%d') > date_format((curdate() - interval 3 day),'%Y%m%d') and f.event_id in(select fa.event_id from fevent_attentions fa where fa.openid='".$_SESSION['openid']."')  order by f.attention_count desc";
			$phpfile=$phpfile.'&arrange=3';
		}else  {  //首页
			$sql = "select f.event_id,f.event_desc,f.event_happened_date,f.attention_count,f.event_detail from fevent_eventcontent  f where  date_format(f.event_happened_date,'%Y%m%d') > date_format((curdate() - interval 3 day),'%Y%m%d') and f.event_isprove=1 and f.event_open_arrage=1 order by f.attention_count desc";
		}
		$eventsListTotal = $this->_model->getEventListNum($sql);
		$getpageinfo = self::page($page,$eventsListTotal,$phpfile);
		$sql.=$getpageinfo['sqllimit'];//组合完整的SQL语句
		$eventsList =  $this->_model->getEventList($sql);
		
		$groupHotList = $this->_groupmodel->getHotGroupList();  //或者热门群组列表
		
		include './templates/indexlist.html';

		// openid BDF65CDFCC0520CD84BD4C4C31A0A241 

	}
	
		//分页公共
	public function page($page,$total,$phpfile,$pagesize=8,$pagelen=4){
		$pagecode = '';//定义变量，存放分页生成的HTML
		$page = intval($page);//避免非数字页码
		$total = intval($total);//保证总记录数值类型正确
		if(!$total) return array();//总记录数为零返回空数组
			$pages = ceil($total/$pagesize);//计算总分页
		//处理页码合法性
		if($page<1) $page = 1;

		if($page>$pages) $page = $pages;
		//计算查询偏移量
		$offset = $pagesize*($page-1);
		//页码范围计算
		$init = 1;//起始页码数
		$max = $pages;//结束页码数
		$pagelen = ($pagelen%2)?$pagelen:$pagelen+1;//页码个数

		$pageoffset = ($pagelen-1)/2;//页码个数左右偏移量

		//生成html
		$pagecode='<div class="page">';
		//$pagecode.="<span>$page/$pages</span>&nbsp;&nbsp;";//第几页,共几页
		//如果是第一页，则不显示第一页和上一页的连接

		if($page!=1){
			$pagecode.='<a href="'.$phpfile.'&page=1">'.'<span class="fenye_le">第一页</span>'.'</a>&nbsp;&nbsp;';
			// $pagecode.="<a href="{$phpfile}?page=1"><<</a>";//第一页
			//$pagecode.="<a href="{$phpfile}?page=".($page-1).""><</a>";//上一页
			$pagecode.='<a href="'.$phpfile.'&page='.($page-1).'">'.'<span class="fenye_le">上一页</span>'.'</a>&nbsp;&nbsp;';
		}
		//分页数大于页码个数时可以偏移
		if($pages>$pagelen){
		//如果当前页小于等于左偏移
		if($page<=$pageoffset){
		$init=1;
		$max = $pagelen;
		}else{//如果当前页大于左偏移
		//如果当前页码右偏移超出最大分页数
		if($page+$pageoffset>=$pages+1){
		$init = $pages-$pagelen+1;
		}else{
			//左右偏移都存在时的计算
			$init = $page-$pageoffset;
			$max = $page+$pageoffset;
		}
		}
		}
		//生成html
		for($i=$init;$i<=$max;$i++){
			if($i==$page){
				$pagecode.='<span><em><a href="#" style="background:url(images/fenye_a_hover.gif) no-repeat; width:36px; height:42px; line-height:42px; display:block; color:#fff;">'.$i.'</a></em></span>&nbsp;&nbsp;';
			} else {
		$pagecode.='<em><a href="'.$phpfile.'&page='.$i.'"  >'.''.$i.'</a></em>';

		}
		}
		if($page!=$pages){
		$pagecode.='<a href="'.$phpfile.'&page='.($page+1).'">'.'<span class="fenye_ri">下一页</span>'.'</a>&nbsp;&nbsp;';
		$pagecode.='<a href="'.$phpfile.'&page='.$pages.'">'.'<span class="fenye_ri">最后一页</span>'.'</a>&nbsp;&nbsp;';
		}

		$pagecode.='</div>';
		return array('pagecode'=>$pagecode,'sqllimit'=>' limit '.$offset.','.$pagesize);
		}


}

$p = new indexlist();
?>
