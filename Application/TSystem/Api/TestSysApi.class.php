<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace TSystem\Api;

use \Common\Api\Api;

class TestSysApi extends Api{
	
	protected function _init(){
		$this->model = new \TSystem\Model\TestSysModel();
	}
	
	/**
	 * 查询出指定组织机构的有效可测评记录
	 */
	public function queryWithOrg($orgids){
      	
		$condition = array("org_id"=>array('in',$orgids));
		$condition['start_time'] = array('lt',time());
		$condition['end_time'] = array('gt',time());
		
		$result = $this->model->alias("ts")->field("ts.id,ts.title,ts.text,ts.desc,ts.eval_ids,ts.creator,ts.creator_orgnames,ts.end_time,ts.start_time,ts.create_time")->join(" left join __ORG_HAS_TEST_SYS__ as hts on hts.test_sys_id = ts.id and ts.status = '".\TSystem\Model\TestSysModel::STATUS_PUBLISH."'")->order(' ts.end_time asc')->where($condition)->select();
		
		if($result === false){
			return $this->apiReturnErr($this->model->getDbError());
		}
		
		return $this->apiReturnSuc($result);
	}
}
