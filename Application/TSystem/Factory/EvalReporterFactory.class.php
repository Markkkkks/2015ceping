<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------


namespace TSystem\Factory;

final class EvalReporterFactory{
	
	const MBTI = "mbti";
	const SCL90 = "scl90";
	
	/**
	 * 统一调用
	 */
	public static function generate($type,$id){
		$factory ;
		switch($type){
			case self::MBTI:
				$factory = new \TSystem\Api\MBTIEvalReporterApi();
				break;
			case self::SCL90:
				$factory = new \TSystem\Api\SCL90EvalReporterApi();
				break;
			default:
				$factory = null;
				break;
		}
		
		if(is_null($factory)){
			return array('status'=>false,'info'=>'不支持的量表类型!');
		}
		
		return $factory->generate(array('id'=>$id));
			
	}
}
