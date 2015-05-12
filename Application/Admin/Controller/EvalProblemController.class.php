<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Admin\Controller;

class EvalProblemController extends AdminController{
	
	private $eval_id = 0;
	
	protected function _initialize(){
		parent::_initialize();
		$this->eval_id = I('get.eval_id',0);
		$this->assign("eval_id",$this->eval_id);
	}
	
	/**
	 * 首页-管理
	 */
	public function index(){
		
		$result = apiCall("TSystem/Evaluation/getInfo", array(array('id'=>$this->eval_id)));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$evaluation = $result['info'];
		
		$map = array(
			'evaluation_id'=>$this->eval_id,
		);
		
		$params = array(
			'evaluation_id'=>$this->eval_id,
		);
		
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		
		$order = " create_time desc ";
		//
		$result = apiCall("TSystem/EvalProblem/query",array($map,$page,$order,$params));
		
		//
		if($result['status']){
			$this->assign('evaluation',$evaluation);
			$this->assign('show',$result['info']['show']);
			$this->assign('list',$result['info']['list']);
			$this->display();
		}else{
			LogRecord('INFO:'.$result['info'],'[FILE] '.__FILE__.' [LINE] '.__LINE__);
			$this->error(L('UNKNOWN_ERR'));
		}
		
	}
	
	/**
	 * 添加
	 */
	public function add(){
		if(IS_GET){
			$map = array('evaluation_id'=>$this->eval_id);
			$field = "sort";
			
			$result = apiCall("TSystem/EvalProblem/max", array($map,$field));
			if(!$result['status']){
				$this->error($result['info']);
			}
			if(is_null($result['info'])){
				$result['info'] = 0;
			}
			$result['info'] = $result['info'] + 1;
			$this->assign("curMaxSort",$result['info']);
			$this->display();
		}else{
			
			$title = I('post.title','');
			$desc = I('post.desc','');
			
			$entity = array(
				'title'=>$title,
				'desc'=>$desc,
				'type'=>I('post.type',0),
				'evaluation_id'=>$this->eval_id,
				'sort'=>I('post.sort',1),
			);
			
			$result = apiCall("TSystem/EvalProblem/add", array($entity));
			
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->success("添加成功!",U('Admin/EvalProblem/index',array('eval_id'=>$this->eval_id)));
		}
	}
	
	/**
	 * 编辑
	 */
	public function edit(){
		
		$id = I('get.id',0);
		
		if(IS_GET){
			$map = array(
				'id'=>$id
			);
			
			$result = apiCall("TSystem/EvalProblem/getInfo", array($map));
			
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->assign("vo",$result['info']);
			$this->display();
		}else{
			
			$title = I('post.title','');
			$desc = I('post.desc','');
			
			$entity = array(
				'title'=>$title,
				'desc'=>$desc,
				'type'=>I('post.type',0),
				'sort'=>I('post.sort',1),
			);
			
			
			$result = apiCall("TSystem/EvalProblem/saveByID", array($id,$entity));
			
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->success("保存成功!",U('Admin/EvalProblem/index',array('eval_id'=>$this->eval_id)));
		}
	}
	
	/**
	 * 删除
	 */
	public function delete(){
			
		if(IS_AJAX){
			$id = I('get.id',0);
			$map = array(
				'id'=>$id
			);
			$result = apiCall("TSystem/EvalAnswer/queryNoPaging", array(array('problem_id'=>$id)));
			
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			if(is_array($result['info']) && count($result['info']) > 0){
				$this->error("请先删除该问题下的答案！");
			}
			
			$result = apiCall("TSystem/EvalProblem/delete", array($map));
			
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->success("删除成功！");
		}else{
			$this->error("禁止访问！");
		}

	}
	
	
	
	
}

