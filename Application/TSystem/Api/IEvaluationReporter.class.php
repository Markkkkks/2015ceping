<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace TSystem\Api;

 /**
 * 量表结果生成接口
 */
interface IEvaluationReporter{
	/**
	 * 根据参数查找，用户的答案，根据答案再生成最终报告
	 * @param $params 参数数组形式
	 */
	function generate($params);
	
	/**
	 * 根据参数查找，用户的报告数据，对数据进行解析并返回
	 * @param array $params 数组
	 */
	function getData($params);
	
	/**
	 * 获取指定用户的测评报告 的解决方案
	 */
	function getSolution($params);
}
