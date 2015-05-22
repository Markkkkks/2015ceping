<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace TSystem\Api;
use \Common\Api\Api;
use TSystem\Model\OrgHasTestSysModel;

/**
 * 
 */
class OrgHasTestSysApi extends Api{
	
	protected function _init(){
		$this->model = new OrgHasTestSysModel();
	}
	
	/**
	 * 
	 * 
	 * @param $orgid 根据组织机构ID，查询出测评信息
	 * @return array 信息
	 */
	public function queryWithTestInfo($orgid,$page,$param){
		
		$query = $this->model->alias("hts")->join("LEFT JOIN __TEST_SYS__ as ts on ts.id = hts.test_sys_id ");
		
		$query = $query->field("ts.title,ts.id,ts.desc,ts.start_time,ts.end_time,ts.create_time,ts.status")->where(array("ts.status"=>"publish","hts.org_id"=>$orgid))->order(" ts.create_time asc");
		
		
		$list = $query -> page($page['curpage'] . ',' . $page['size']) -> select();
		
		if ($list === false) {
			$error = $this -> model -> getDbError();
			return $this -> apiReturnErr($error);
		}
		
		$count = $this -> model -> where($map) -> count();
		// 查询满足要求的总记录数
		$Page = new \Think\Page($count, $page['size']);

		//分页跳转的时候保证查询条件
		if ($params !== false) {
			foreach ($params as $key => $val) {
				$Page -> parameter[$key] = urlencode($val);
			}
		}
		
		// 实例化分页类 传入总记录数和每页显示的记录数
		$show = $Page -> show();
		
		return $this -> apiReturnSuc(array("show" => $show, "list" => $list));
	}
		
}
