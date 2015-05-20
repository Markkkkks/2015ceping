<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace TSystem\Api;
use \Common\Api\Api;

class TestevalUserAnswerApi extends Api{
	
	protected function _init(){
		$this->model = new \TSystem\Model\TestevaluserAnswerModel();
	}
	
}
