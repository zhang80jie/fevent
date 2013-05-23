<?php

//引入公共初始化文件 
require dirname(dirname(__FILE__)) . '/include/lib/init.php';

/**
 * 用户个人详情页
 */
class help{
	

	public function __construct() {
		if (method_exists($this, 'init')) {
			$this->init();
		}
	}

	protected function init() {
		include './templates/help.html';
	}
	
	
}
$p = new help();
?>
