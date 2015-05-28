<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace TSystem\Api;

use \Common\Api\Api;

class EvalProblemApi extends Api{
	
	protected function _init(){
		$this->model = new \TSystem\Model\EvalProblemModel();
	}
	
	
	public function queryWithAnswer($eval_id){
		
		$result = $this->model->alias("ep")->field("ep.id,ep.title,ep.desc,ep.create_time,ep.sort,ea.title as ea_title,ea.hidden_value as ea_hidden_value,ea.icon_url as ea_icon_url,ea.explain as ea_explain,ea.sort as ea_sort,ea.id as ea_id")->join("LEFT JOIN __EVAL_ANSWER__ as ea on ep.id = ea.problem_id")->where(array("ep.evaluation_id"=>$eval_id))->order(" ep.sort asc,ea.sort asc ")->select();
		
		if($result === false){
			return $this->apiReturnErr($this->model->getDbError());
		}
		
		return $this->apiReturnSuc($result);
		
	}
	
	public function prev($map){
		$result = $this->model->where($map)->order(" sort desc ")->find();
		
		if($result === false){
			return $this->apiReturnErr($this->model->getDbError());
		}
		
		return $this->apiReturnSuc($result);
	}
	
}
