<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace TSystem\Controller;

class TestController extends  \Think\Controller{
	
	public function index(){
		
//		$facade = new ();
//		$params = array(
//			'type'=>,
//			'uid'=>34,
//			'testid'=>0,
//			'evalid'=>1,
//		);
		$result = \TSystem\Factory\EvalReporterFactory::generate(\TSystem\Factory\EvalReporterFactory::SCL90,11);
		dump($result);
		
		
	}
	
}

