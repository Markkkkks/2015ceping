<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace TSystem\Api;
use \Common\Api\Api;

class TestevalUserResultApi extends Api{
	
	protected function _init(){
		$this->model = new \TSystem\Model\TestevaluserResultModel();
	}
	
	/**
	 * 上一个
	 */
	public function prev($orgid,$test_id,$eval_id,$id){
		
		$query = $this->model->alias("tur");
		
		$map = array('tur.id'=>array("lt",$id),'tur.test_id'=>$test_id,'tur.eval_id'=>$eval_id,"tur.org_ids"=>array('like','%'.$orgid.',%'));
		
		$query = $query->where($map);
		
		$result = $query->order("tur.id desc")->find();
		
		if ($result === false) {
			$error = $this -> model -> getDbError();
			return $this -> apiReturnErr($error);
		}
		
		return $this->apiReturnSuc($result);
		
	}

  
 	/**
	 * 下一个
	 */
	public function next($orgid,$test_id,$eval_id,$id){
		
		$query = $this->model->alias("tur");
		
		$map = array('tur.id'=>array("gt",$id),'tur.test_id'=>$test_id,'tur.eval_id'=>$eval_id,"tur.org_ids"=>array('like','%'.$orgid.',%'));
		
		$query = $query->where($map);
		
		$result = $query->order("tur.id asc")->find();
		
		if ($result === false) {
			$error = $this -> model -> getDbError();
			return $this -> apiReturnErr($error);
		}
		
		return $this->apiReturnSuc($result);
		
	}
	
	/**
	 * 查询审核记录
	 * @param  int $orgid 组织机构ID
	 * @param int $eval_id 量表ID
	 * @param int $test_id 测评ID
	 * */
	public function queryWithUserInfo($orgid,$test_id,$eval_id, $page = array('curpage'=>0,'size'=>10), $params = false){
		
		$query = $this->model->alias("tur");
		
		$map = array('tur.test_id'=>$test_id,'tur.eval_id'=>$eval_id,"tur.org_ids"=>array('like','%'.$orgid.',%'));
		
		$query = $query->where($map);
		
		$query = $query->order("tur.create_time asc")->field(" tur.user_id,tur.eval_type,tur.eval_id,tur.id,tur.test_id,tur.create_time,tur.review,tur.review_notes ");
		
		$list = $query -> page($page['curpage'] . ',' . $page['size']) -> select();
//		dump($this->model->getLastSql());
		if ($list === false) {
			$error = $this -> model -> getDbError();
			return $this -> apiReturnErr($error);
		}
		
		$count = $query -> count();
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
	
	public function queryWithTestEval($user_id,$title, $page = array('curpage'=>0,'size'=>10), $params = false) {
		$query = $this->model->alias("tur")->join("LEFT JOIN __EVALUATION__ as ev on ev.id = tur.eval_id");
		
		$query = $query->where(array("tur.user_id"=>$user_id,'ev.title'=>array("like" , "%".$title."%")));
		
		
		$query = $query->order("create_time asc")->field(" ev.title,tur.user_id,tur.eval_type,tur.eval_id,tur.id,tur.test_id,tur.create_time,tur.review,tur.review_notes ");
		
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
