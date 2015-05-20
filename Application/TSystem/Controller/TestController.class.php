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
		
		$type = \TSystem\Facade\EvalReporterFacade::SCL90;
		$result = \TSystem\Facade\EvalReporterFacade::generate(\TSystem\Facade\EvalReporterFacade::SCL90,34,0,1);
		dump($result);
		$result = \TSystem\Facade\EvalReporterFacade::generate(\TSystem\Facade\EvalReporterFacade::MBTI,34,0,1);
		dump($result);
		$this->assign("time",date("Y-m-d H:i:s",time()));
		
		$this->display("r_".$type);
		
	}
	
}

