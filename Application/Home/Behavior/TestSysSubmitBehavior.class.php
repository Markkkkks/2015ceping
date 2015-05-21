<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Home\Behavior;
use Think\Behavior;

/**
 * 测试提交后，进行
 */
class TestSysSubmitBehavior extends Behavior {
	
     // 行为扩展的执行入口必须是run
     public function run(&$params){
     	if(!(is_array($params) && isset($params['id']))){
     		LogRecord("TestSysSubmit行为参数错误！", __FILE__.__LINE__);
     		return ;
     	}
		
		//生成报告
		$id = $params['id'];
				
		$result = \TSystem\Facade\EvalReporterFacade::generate(array('id'=>$id));
		
		dump($result);
		
     }
}

