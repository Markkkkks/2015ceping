<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Admin\Controller;

class EvalAnswerController extends AdminController{
	//量表ID
	private $eval_id = 0;
	//问题ID
	private $problem_id = 0;
	
	protected function _initialize(){
		parent::_initialize();
		$this->problem_id = I('get.problem_id',0);
		$this->eval_id = I('get.eval_id',0);
		$this->assign("problem_id",$this->problem_id);
		$this->assign("eval_id",$this->eval_id);
	}
	
	/**
	 * 首页-管理
	 */
	public function index(){
		
		$result = apiCall("TSystem/EvalProblem/getInfo", array(array('id'=>$this->problem_id)));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$evalProblem = $result['info'];
		
		$result = apiCall("TSystem/EvalProblem/getInfo", array(array('evaluation_id'=>$evalProblem['evaluation_id'],'sort'=>array("gt",$evalProblem['sort']))));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$nextEvalProblem = $result['info'];
		
		$result = apiCall("TSystem/EvalProblem/prev", array(array('evaluation_id'=>$evalProblem['evaluation_id'],'sort'=>array("lt",$evalProblem['sort']))));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$prevEvalProblem = $result['info'];
		
		$map = array(
			'problem_id'=>$this->problem_id,
		);
		
		$params = array(
			'eval_id'=>$this->eval_id,
			'problem_id'=>$this->problem_id,
		);
		
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		
		$order = " sort asc,create_time desc ";
		//
		$result = apiCall("TSystem/EvalAnswer/query",array($map,$page,$order,$params));
		
		//
		if($result['status']){
			$this->assign('prevEvalProblem',$prevEvalProblem);
			$this->assign('nextEvalProblem',$nextEvalProblem);
			$this->assign('problem',$evalProblem);
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
			
			$map = array('problem_id'=>I("get.problem_id",0));
			$field = "sort";
			
			$result = apiCall("TSystem/EvalAnswer/max", array($map,$field));
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
			$explain = I('post.explain','');
			$hidden_value = I('post.hidden_value','');
			$icon_url = I('post.icon_url','');
			
			$entity = array(
				'title'=>$title,
				'desc'=>$desc,
				'icon_url'=>$icon_url,
				'explain'=>$explain,
				'hidden_value'=>$hidden_value,
				'problem_id'=>$this->problem_id,
				'evaluation_id'=>$this->eval_id,
				'sort'=>I('post.sort',1),
			);
			
			$result = apiCall("TSystem/EvalAnswer/add", array($entity));
			
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->success("添加成功!",U('Admin/EvalAnswer/index',array('problem_id'=>$this->problem_id,'eval_id'=>$this->eval_id)));
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
			
			$result = apiCall("TSystem/EvalAnswer/getInfo", array($map));
			
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->assign("vo",$result['info']);
			
			
			$this->display();
		}else{
			
			$title = I('post.title','');
			$explain = I('post.explain','');
			$hidden_value = I('post.hidden_value','');
			$icon_url = I('post.icon_url','');
			
			$entity = array(
				'title'=>$title,
				'desc'=>$desc,
				'icon_url'=>$icon_url,
				'explain'=>$explain,
				'hidden_value'=>$hidden_value,
				'problem_id'=>$this->problem_id,
				'evaluation_id'=>$this->eval_id,
				'sort'=>I('post.sort',1),
			);
			
			
			$result = apiCall("TSystem/EvalAnswer/saveByID", array($id,$entity));
			
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->success("保存成功!",U('Admin/EvalAnswer/index',array('problem_id'=>$this->problem_id,'eval_id'=>$this->eval_id)));
		}
	}
	
	/**
	 * 删除
	 */
	public function delete(){
			
		if(IS_AJAX){
			$id = I('get.id',0);
			
			$result = apiCall("TSystem/EvalAnswer/delete", array(array('id'=>$id)));
			
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->success("删除成功！");
		}else{
			$this->error("禁止访问！");
		}

	}
	
	
	
	
}

