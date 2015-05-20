<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------


namespace TSystem\Api;

class SCL90EvalReporterApi implements IEvaluationReporter{
	/**
	 * 该报告生成对应的量表类型
	 */
	const EVAL_TYPE = 25;
	
	/**
	 * 数组形式 
	 * @param array $params  array('evalid'=>'量表ID','uid'=>'用户ID','testid'=>'测评ID')
	 */
	public function generate($params){
		
		if(!is_array($params) || !isset($params['evalid']) || !isset($params['testid']) || !isset($params['uid'])){
			trigger_error("缺少参数!");
		}
		
		$map = array(
			'user_id'=>$params['uid'],
			'test_id'=>$params['testid'],
			'eval_type'=>self::EVAL_TYPE,
			'eval_id'=>$params['evalid'],
		);
		
		$eval_id = $params['eval_id'];
		$result = apiCall("TSystem/TestevalUserAnswer/getInfo", array($map));
		if(!$result['status']){
			return $this->returnErr($result['info']);
		}
		$entity = $result['info'];//取得用户选择的答案
		$answer = unserialize($entity['answer']);
		//答案需要包含信息
		//1. 问题编号
		//2. 答案编号，答案隐藏值,
		//========================进行SCL90量表的分析并生成相应数据======
		$result = array();
		
		//============================================================
		return $this->returnSuc($result);
	}
	
	private function returnErr($info){
		return array('status'=>false,'info'=>$info);
	}
	
	private function returnSuc($info){
		return array('status'=>true,'info'=>$info);
	}
	
	
	
}
