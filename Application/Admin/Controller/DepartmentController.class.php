<?php
/**
 * (c) Copyright 2014 hebidu. All Rights Reserved. 
 */

namespace Admin\Controller;

class DepartmentController extends AdminController {

	//首页
    public function index(){
       
        $where='level=3';
		$list=M('organization','common_')->where($where)->select();
//		dump($list);
		$this->assign('list',$list);
		$this->display();
    }
	
	public function add(){
		
		if(IS_GET){
			$uid=session('uid');
			$where='member_uid='.$uid;
			$list=M('org_member','common_')->where($where)->getField('organization_id');
			$where1='id='.$list;
			$list1=M('organization','common_')->where($where1)->select();
			$this->assign('list1',$list1);
			$this->display();
		}else{
			$part=I('type','');
			$result = apiCall("Admin/Organization/getInfo",array(array('id'=>$part)));
			$level = 0;
			$parents =$part.',';
			if($result['status'] && is_array($result['info'])){
				$level = intval($result['info']['level'])+1;
				$parents = $result['info']['path'].$parents;
			}
			$entity = array(
				'orgcode'=>'',
				'orgname'=>I('name',''),
				'notes'=>I('notes',''),
				'sort'=>I('sort',''),
				'type'=>I('type',''),
				'level'=>$level,
				'path'=>$parents,
				'father'=>I('father',''),
			);
			
			$result = apiCall("Admin/Organization/add", array($entity));
			
			if(!$result['status']){
				$this->error($result['info']);
			}

			$this->success("操作成功！",U('Admin/Department/index',array('parent'=>$part)));
		}
	}
public function delete(){
		$id = I('id',0);
		
		$result = apiCall("Admin/Organization/queryNoPaging", array(array('father'=>$id)));
		if(!$result['status']){
			$this->error($result['info']);
		}
		if(is_array($result['info']) && count($result['info']) > 0){
			$this->error("有子机构，请先删除所有子机构！");
		}
		
		$result = apiCall("Admin/OrgMember/query", array(array('organization_id'=>$id)));
		if(!$result['status']){
			$this->error($result['info']);
		}
		if(is_array($result['info']['list']) && count($result['info']['list']) > 0){
			$this->error("存在机构成员，请先移除所有机构的成员！");
		}
		
		$result = apiCall("Admin/Organization/delete", array(array('id'=>$id)));
		if(!$result['status']){
			$this->error($result['info']);
		}

		$this->success("操作成功！");
		
		
	}
	
	public function edit(){
		$id = I('get.id',0);
		if(IS_GET){
			$result = apiCall("Admin/Organization/getInfo",array(array('id'=>$id)));
			if($result['status']){
				$this->assign("entity",$result['info']);
			}
			$lists=M('datatree','common_')->where('parentid=202')->select();
			$this->assign('lists',$lists);
			$this->display();
		}else{
		
			$entity = array(
				'orgname'=>I('name',''),
				'type'=>I('type',''),
				'notes'=>I('notes',''),
				'sort'=>I('sort',''),
				'code'=>I('code',''),
			);
			$result = apiCall("Admin/Organization/saveByID", array($id,$entity));
			
			if(!$result['status']){
				$this->error($result['info']);
			}

			$this->success("操作成功！",U('Admin/Department/index',array('parent'=>$this->parent)));
			
		}
	}
	
}