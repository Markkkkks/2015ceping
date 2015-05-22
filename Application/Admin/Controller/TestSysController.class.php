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
	
	/**
	 * 发布
	 */
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

	 
	 /**
	 * 更改为草稿
	 */
	public function draft(){
		
		$id = I("get.id",0);
		$saveEntity = array(
			'status'=>\TSystem\Model\TestSysModel::STATUS_DRAFT,
		);
		
		$result = apiCall("TSystem/TestSys/saveByID", array($id,$saveEntity));
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$this->success("变更成功！");
	}
	
	public function evaluation(){
		if(IS_GET){
			$id = I('get.id',0);
			
			$map = array(
				'status'=>1,
			);
			$result = apiCall("TSystem/Evaluation/queryNoPaging", array($map));
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$eval_list = $result['info'];
		
			$result = apiCall("TSystem/TestSys/getInfo", array(array("id"=>$id)));
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->assign("test",$result['info']);
			
			$map = array();
			$map['id'] = array('in',$result['info']['eval_ids']);
			$result = apiCall("TSystem/Evaluation/queryNoPaging", array($map));
			
			$choosed_evals =  $this->getChoosedEvals($result['info']);
			
			$this->assign("choosed_evals",$choosed_evals);
			$this->assign("id",$id);
			$this->assign("eval_list",$eval_list);
			$this->display();
		}else{
			$id = I("get.id",0);
			$evalids = I('post.evalids','');
			
			$saveEntity = array('eval_ids'=>$evalids);
			$result = apiCall("TSystem/TestSys/saveByID", array($id,$saveEntity));
			
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->success("保存成功!",U('Admin/TestSys/index'));
			
		}
	}

	
	/**
	 * 组织机构
	 * TODO:
	 */
	public function org(){
		$id = I('get.id',0);
		if(IS_GET){
			$this->assign("id",$id);
			$this->display();
		}else{
			
			$org_ids = I('post.org_ids','');
//			$saveEntity = array(
//				'org_ids'=>$org_ids,
//			);
			
			$org_id_arr = explode(",", $org_ids);
			$array = array();
			$curtime = time();
			foreach($org_id_arr as $vo){
				if(empty($vo)){
					continue;
				}
				array_push($array,array('test_sys_id'=>$id,'org_id'=>$vo,'create_time'=>$curtime));
			}
			
			$result = apiCall("TSystem/OrgHasTestSys/delete", array(array('test_sys_id'=>$id)));
			if(!$result['status']){
				$this->error($result['info']);
			}
			$result = apiCall("TSystem/OrgHasTestSys/addAll", array($array));
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->success("保存成功! ",U('Admin/TestSys/index'));
			
		}
	}

	/**
	 * 组织机构
	 * ajax 获取
	 * TODO: 如果性能差，考虑逐级加载
	 */
	public function org_json(){
		$id = I('get.id',0);
		$testid = I('get.testid',0);
		$map = array();
//		$map['father'] = $id;
		
		$result = apiCall("Admin/Organization/queryNoPaging", array($map));
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$orglist = $result['info'];
		
		$result = apiCall("TSystem/OrgHasTestSys/queryNoPaging", array(array("test_sys_id"=>$testid)));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		$orgids = "";
		
		foreach($result['info'] as $vo){
			$orgids .= $vo['org_id'].',';
		}
		//checked:true		
		
//		$orgids = $result['info']['org_ids'];
		
		$json = array();
		foreach($orglist as $vo){
			$one = array(
				'id'=>$vo['id'],
				'father'=>$vo['father'],
				'name'=>$vo['orgname'],
			);
			//3级以内都展开
			if($vo['level'] < 2){
				$one['open'] = true;
			}
			$needle = $vo['id'].",";
			if(!(strpos($orgids, $needle) === false)){
				$one['checked'] = true;
			}
			array_push($json,$one);
		}
		
		$this->success($json);
	}

	/**
	 * 测评结果的审核
	 * TODO: 针对参与测评用户的结果的审校
	 */
	public function review(){
		
		// 组织机构是可以查看的用户的上级或同级别。
		$orgid = I('post.orgid',0);
		$testid = I('post.testid',0);
		if($orgid == 0){
			$orgid = I("get.orgid",0);
		}
		if($testid == 0){
			$testid = I("get.testid",0);
		}
		// 1. 先查出下属以及同级的组织机构 
		// 2. 再从机构用户表中查找出隶属于这些机构的用户ID
		// 3. 再从测评答案表中查找测评记录 
		// 4. 
		$result = apiCall("Admin/OrgMemberView/queryNoPaging", array(array("member_id"=>UID)));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$org_arr = $result['info'];//当前用户的组织机构ID
		
		if($orgid == 0 && count($org_arr)>0){
			$orgid = $org_arr[0]['orgid'];
		}
		
		$page =  array("curpage"=>0,'size'=>10);
		$params = array(
			'orgid'=>$orgid,
			'testid'=>$testid,
		);
		
		$result = apiCall("TSystem/OrgHasTestSys/queryWithTestInfo", array($orgid,$page,$params));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		
		$this->assign("org_arr",$org_arr);
		$this->assign("orgid",$orgid);
		$this->assign("testid",$testid);
		
		$this->assign("show",$result['info']['show']);
		$this->assign("list",$result['info']['list']);
		$this->display();
	}


	/**
	 * 查看单个测评-指定机构的参与测评用户的结果
	 */
	public function result(){
		$org_id = I('get.org_id',0);
		$test_id = I('get.test_id',0);
		$eval_id = I('get.eval_id',0);
		if($orgid == 0){
			$orgid = I("get.orgid",0);
		}
		if($testid == 0){
			$testid = I("get.testid",0);
		}
		
		
		$page = array('curpage'=>I("get.p",0),"size"=>10);
		$params = array(
			'org_id'=>$org_id,
			'test_id'=>$test_id,
		);
		
		$result = apiCall("TSystem/TestevalUserResult/queryWithUserInfo", array($org_id,$test_id,$page,$params));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		
		$this->assign("eval_id",$eval_id);
		$this->assign("orgid",$orgid);
		$this->assign("testid",$testid);
		$this->assign("show",$result['info']['show']);
		$this->assign("list",$result['info']['list']);
		$this->display();
		
	}






	//===============
	
	private  function getChoosedEvals($info){
		$result = array();
		foreach($info as $vo){
			array_push($result,array('id'=>$vo['id'],'text'=>$vo['title']));	
		}
		return json_encode($result);
	}


	
}
