<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Admin\Controller;

class EvaluationController extends AdminController{
	
	protected function _initialize(){
		parent::_initialize();
	}
	
	
	/**
	 * 首页-管理
	 */
	public function index(){
		
		$map = array();
		
		$params = array();
		
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		
		$order = " create_time desc ";
		//
		$result = apiCall("TSystem/Evaluation/query",array($map,$page,$order,$params));
		
		//
		if($result['status']){
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
			
			$map['parentid'] = getDatatree('TEST_TABLE_APPLICABLE');
			$result = apiCall('Admin/Datatree/queryNoPaging',array($map));
			
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->assign("roles",$result['info']);
			$this->display();
		}else{
			
			$title = I('post.title','');
			$desc = I('post.desc','');
			$roles = I('post.roles',array());
			$roles_str = "";
			foreach($roles as $vo){
				$roles_str .= $vo.',';
			}
			
			$entity = array(
				'title'=>$title,
				'desc'=>$desc,
				'type'=>I('post.type',0),
				'user_id'=>UID,
				'roles'=>$roles_str
			);
			
			$result = apiCall("TSystem/Evaluation/add", array($entity));
			
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->success("添加成功!",U('Admin/Evaluation/index'));
		}
	}
	
	/**
	 * 编辑
	 */
	public function edit(){
		
		$id = I('get.id',0);
		if(IS_GET){
			$result = apiCall("TSystem/Evaluation/getInfo", array(array("id"=>$id)));
			if(!$result['status']){
				$this->error($result['info']);
			}
			$this->assign("vo",$result['info']);
			$map['parentid'] = getDatatree('TEST_TABLE_APPLICABLE');
			$result = apiCall('Admin/Datatree/queryNoPaging',array($map));
			
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->assign("roles",$result['info']);
			$this->display();
		}else{
			
			$title = I('post.title','');
			$desc = I('post.desc','');
			$roles = I('post.roles',array());
			$roles_str = "";
			foreach($roles as $vo){
				$roles_str .= $vo.',';
			}
			
			$entity = array(
				'title'=>$title,
				'desc'=>$desc,
				'type'=>I('post.type',0),
				'roles'=>$roles_str
			);
			$result = apiCall("TSystem/Evaluation/saveByID", array($id,$entity));
			
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->success("保存成功!",U('Admin/Evaluation/index'));
		}
	}
	
	/**
	 * 删除
	 */
	public function delete(){
		$id = I('get.id',0);
		$map = array(
			'evaluation_id'=>$id,
		);
		$result = apiCall("TSystem/EvalProblem/query", array($map));
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		if(count($result['info']['list']) > 0){
			$this->error("请先删除量表所属的问题！");
		}
			
		
		$result = apiCall("TSystem/Evaluation/delete", array(array('id'=>$id)));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$this->success("删除成功！");
				
	}
	
	
	
	
	
	
}

