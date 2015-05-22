<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace TSystem\Model;
use Think\Model;

class TestevalUserResultModel extends Model{
	
	/**
	 * 待审核
	 */
	const REVIEW_STATUS_WAIT_CHECK = 0;
	
	/**
	 * 已通过审核
	 */
	const REVIEW_STATUS_PASS = 1;
	
	/**
	 * 驳回
	 */
	const REVIEW_STATUS_DENY = 2;
	
	protected $_auto = array(
		array("create_time",NOW_TIME,self::MODEL_INSERT),
	);
		
}
