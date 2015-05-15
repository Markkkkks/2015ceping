<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------


namespace TSystem\Facade;

class EvalReporterFacade{
	
	const MBTI = "mbti";
	const SCI90 = "sci90";
	/**
	 * 统一调用
	 */
	public function generate($type,$uid,$testid,$evalid){
		$factory ;
		switch($type){
			case self::MBTI:
				$factory = new \TSystem\Api\MBTIEvalReporter();
				break;
			case self::SCI90:
				$factory = new \TSystem\Api\SCI90EvalReporter();
				break;
			default:
				$factory = null;
				break;
		}
		
		if(is_null($factory)){
			return array('status'=>false,'info'=>'不支持的量表类型!');
		}
		
		return $factory->generate(array('uid'=>$uid,'testid'=>$testid,'evalid'=>$evalid));
			
	}
}
