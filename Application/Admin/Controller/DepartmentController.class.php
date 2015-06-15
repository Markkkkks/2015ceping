<?php
/**
 * (c) Copyright 2014 hebidu. All Rights Reserved. 
 */

namespace Admin\Controller;

class DepartmentController extends AdminController {

	//首页
    public function index(){
    	$map = array();
		$uid=session('uid',"");
		if(!empty($uid)){
			$map['member_uid'] = $uid;
		}
		$fields='organization_id';

		$result=apiCall('Admin/OrgMember/queryNoPaging',array($map,$fields));
		
		if($result['status']){
			$a=$result['info'][0]['organization_id'];
			unset($map);
			$map['id']=$a;
			$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
			$results = apiCall('Admin/Organization/query',array($map,$page));
//			dump($results);
			if($result['status']){
				$this->assign('show',$results['info']['show']);
				$this->assign('list',$results['info']['list']);
				$this->display();
			}else{
				LogRecord('INFO:'.$result['info'],'[FILE] '.__FILE__.' [LINE] '.__LINE__);
				$this->error(L('UNKNOWN_ERR'));
			}
		}
	
    }
	public function click(){
		$map ['father'] = I('parent');
		if(!empty($name)){
			$map['orgname'] = array('like',"%$name%");
			$params['orgname'] = $name;
		}
//		dump($map);
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
		
		$order = " sort desc ";
		//
		$result = apiCall("Admin/Organization/query",array($map,$page,$order,$params));
		//
		if($result['status']){
			$this->assign('parent',I('parent'));
			$this->assign('show',$result['info']['show']);
			$this->assign('list',$result['info']['list']);
			$this->display('index');
		}else{
			LogRecord('INFO:'.$result['info'],'[FILE] '.__FILE__.' [LINE] '.__LINE__);
			$this->error(L('UNKNOWN_ERR'));
		}
	}
	
	public function add(){
		
		if(IS_GET){
			$part=I('parent','');
			$map = array();
			$uid=session('uid',"");
			if(!empty($uid)){
				$map['member_uid'] = $uid;
			}
			$fields='organization_id';
			$result=apiCall('Admin/OrgMember/queryNoPaging',array($map,$fields));
			$this->assign('parent',$part);
			if($result['status']){
				$a=$result['info'][0]['organization_id'];
				unset($map);
				$map['id']=$a;
				$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
				$results = apiCall('Admin/Organization/query',array($map,$page));
				unset($map);
				$map['parentid']=202;
				$list=apiCall('Admin/Datatree/queryNoPaging',array($map));
				$this->assign('list',$list['info']);
			
	//			dump($results);
				if($result['status']){
					$this->assign('show',$results['info']['show']);
					$this->assign('list1',$results['info']['list']);
					$this->display();
				}else{
					LogRecord('INFO:'.$result['info'],'[FILE] '.__FILE__.' [LINE] '.__LINE__);
					$this->error(L('UNKNOWN_ERR'));
				}
			}
		}else{
			$part=I('parent','');
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
				'father'=>$part,
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