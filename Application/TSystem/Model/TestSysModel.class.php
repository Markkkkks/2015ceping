<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.2015-05-15
// |-----------------------------------------------------------------------------------


namespace TSystem\Model;
use Think\Model;
/**
 * 量表、问题模型
 */
class TestSysModel extends Model{
	/**
	 * 草拟中
	 */
	const STATUS_DRAFT = "draft";
	/**
	 * 正式发布
	 */
	const STATUS_PUBLISH = "publish";
	
	protected $_auto = array(
		array('create_time',NOW_TIME,self::MODEL_INSERT),
		array('status',"draft",self::MODEL_INSERT),
	);
}
