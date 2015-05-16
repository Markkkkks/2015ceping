<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Admin\Controller;

class TestSysController extends AdminController{
	
	public function index(){
		$name = I('post.name','');
		if(empty($name)){
			$name = I('get.name','');
		}
		
		$map =  array(
			'creator'=>UID,
		);
		$page = array('curpage'=>I('get.p',0),'size'=>C("LIST_ROWS"));
		
		if(!empty($name)){
			$map['title'] = array('like','%'.$name.'%');
			$params = array('name'=>$name);
		}
		$order = " create_time desc ";
		$result = apiCall("TSystem/TestSys/query", array($map,$page,$order,$params));
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$this->assign("name",$name);
		$this->assign("list",$result['info']['list']);
		$this->assign("show",$result['info']['show']);
		$this->display();
	}
	
	public function add(){
		if(IS_GET){
			$startdatetime = date("Y-m-d",time());
			$enddatetime = date("Y-m-d",time()+24*3600*7);		
			$this->assign("startdatetime",$startdatetime);		
			$this->assign("enddatetime",$enddatetime);	
			$this->display();
		}else{
			$map = array(
				'member_uid'=>UID,
			);
			$result = apiCall("Admin/OrgMemberView/queryNoPaging", array($map));
			if(!$result['status']){
				$this->error($result['info']);
			}
			$orgids = "";
			$orgnames = "";
			foreach($result['info'] as $vo){
				$orgids .= $vo['orgid'].',';
				$orgnames .= $vo['orgname'].',';
			}
			
			
			
			$startdatetime = I('post.startdatetime','','strtotime');
			$enddatetime = I('post.enddatetime','','strtotime');
			$title = I('post.title','');
			$desc = I('post.desc','');
			$text = I('post.text','');
			
			$entity = array(
				'title'=>$title,
				'desc'=>$desc,
				'text'=>$text,
				'creator_orgname'=>$orgid,
				'creator_orgid'=>$orgid,
				'start_time'=>$startdatetime,
				'end_time'=>$enddatetime,
				'creator'=>UID,
				'eval_ids'=>'',
				'org_ids'=>'',
				'creator_orgids'=>$orgids,
				'creator_orgnames'=>$orgnames,
			);
			
			$result = apiCall("TSystem/TestSys/add", array($entity));
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->success("添加成功！",U('Admin/TestSys/index'));
			
		}
	}

	public function edit(){
		if(IS_GET){
			$map = array('id'=>I('get.id',0));
			$result = apiCall("TSystem/TestSys/getInfo", array($map));
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			if(is_null($result['info'])){
				$this->error("参数错误！");
			}
			
			$result['info']['start_time'] = date("Y-m-d",$result['info']['start_time']);
			$result['info']['end_time'] = date("Y-m-d",$result['info']['end_time']);
			
			$this->assign("vo",$result['info']);
			
			$this->display();
		}else{
			$id = I('post.id',0);
			$map = array(
				'member_uid'=>UID,
			);
			$result = apiCall("Admin/OrgMemberView/queryNoPaging", array($map));
			if(!$result['status']){
				$this->error($result['info']);
			}
			$orgids = "";
			$orgnames = "";
			foreach($result['info'] as $vo){
				$orgids .= $vo['orgid'].',';
				$orgnames .= $vo['orgname'].',';
			}
			
			
			$startdatetime = I('post.startdatetime','','strtotime');
			$enddatetime = I('post.enddatetime','','strtotime');
			$title = I('post.title','');
			$desc = I('post.desc','');
			$text = I('post.text','');
			
			$entity = array(
				'title'=>$title,
				'desc'=>$desc,
				'text'=>$text,
				'start_time'=>$startdatetime,
				'end_time'=>$enddatetime,
				'creator_orgids'=>$orgids,
				'creator_orgnames'=>$orgnames,
			);
			
			$result = apiCall("TSystem/TestSys/saveByID", array($id,$entity));
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->success("保存成功！",U('Admin/TestSys/index'));
			
		}
	}
	


	public function view(){
		if(IS_GET){
			$map = array('id'=>I('get.id',0));
			$result = apiCall("TSystem/TestSys/getInfo", array($map));
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			if(is_null($result['info'])){
				$this->error("参数错误！");
			}
			
			$result['info']['start_time'] = date("Y-m-d",$result['info']['start_time']);
			$result['info']['end_time'] = date("Y-m-d",$result['info']['end_time']);
			
			$this->assign("vo",$result['info']);
			
			$this->display();
		}
	}
	
	public function delete(){
		$id = I("get.id",0);
		$status = I('get.status',\TSystem\Model\TestSysModel::STATUS_PUBLISH);
		if($status == \TSystem\Model\TestSysModel::STATUS_PUBLISH){
			$this->error("正式发布后，无法删除测验！");
		}
		
		$result = apiCall("TSystem/TestSys/delete", array(array('id'=>$id)));
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$this->success("删除成功！");
	
	}
	
	public function publish(){
		
		$id = I("get.id",0);
		$saveEntity = array(
			'status'=>\TSystem\Model\TestSysModel::STATUS_PUBLISH,
		);
		
		$result = apiCall("TSystem/TestSys/saveByID", array($id,$saveEntity));
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$this->success("发布成功！");
	}
	
}
