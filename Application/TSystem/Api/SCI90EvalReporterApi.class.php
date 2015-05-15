<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------


namespace TSystem\Api;

class SCI90EvalReporter implements IEvaluationReporter{
	
	public function generate($params){
		$result = array('status'=>false,'info'=>'暂未实现SCI-90量表的报告生成方法！');
		
		return $result;
	}
}
