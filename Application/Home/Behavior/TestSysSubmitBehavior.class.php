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
		$type = $params['type'];
		$uid = $params['user_id'];
		$eval_id = $params['eval_id'];
		$test_id = $params['test_id'];
		$org_ids =  $params['org_ids'];
//		dump("test");
		$result = \TSystem\Factory\EvalReporterFactory::generate($type,$id);
//		exit();
		if($result['status']){
			//将报告结果保存到数据库中
			
			$entity = array(
				'result'=>serialize($result['info']),
				'eval_type'=>$type,
				'eval_id'=>$eval_id,
				'test_id'=>$test_id,
				'user_id'=>$uid,
				'review'=>\TSystem\Model\TestevalUserResultModel::REVIEW_STATUS_WAIT_CHECK,
				'review_notes'=>'',
				'review_time'=>0,
				'org_ids'=>$org_ids,
			);
			
			$result = apiCall("TSystem/TestevalUserResult/add", array($entity));
			
			if(!$result['status']){
				LogRecord("量表报告数据插入数据库失败!", __FILE__.__LINE__);
			}
			
		}else{
			LogRecord("量表报告生成失败!", __FILE__.__LINE__);
		}
		
		
     }
}

