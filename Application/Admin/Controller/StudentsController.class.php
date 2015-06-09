<?php
/**
 * (c) Copyright 2014 hebidu. All Rights Reserved. 
 */

namespace Admin\Controller;

class StudentsController extends AdminController {

	//首页
    public function index(){
      $page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
       $list=apiCall("Admin/Students/query",array($page));
	   $this->assign('list',$list['info']['list']);
	   $this->assign('show',$list['info']['show']);
//	   dump($list);
	   $this->display();
    }
	
	public function add(){
		if(IS_GET){
			$part=I('parent','');
			$map = array();
			$uid=session('uid',"");
			if(!empty($uid)){
				$map['member_uid'] = $uid;
			}
			
			$result=apiCall('Admin/OrgMember/queryNoPaging',array($map));
			
			if($result['status']){
				$a=$result['info'][0]['organization_id'];
				unset($map);
				$map=" path like '%$a%'";
				$results = apiCall('Admin/Organization/queryNoPaging',array($map));
				if($result['status']){
					$this->assign('list',$results['info']);
					$this->display();
				}else{
					LogRecord('INFO:'.$result['info'],'[FILE] '.__FILE__.' [LINE] '.__LINE__);
					$this->error(L('UNKNOWN_ERR'));
				}
			}
		}else{
			$entity = array(
				'name'=>I('name',''),
				'sex'=>I('sex',''),
				'age'=>I('age',''),
				'enrol_year'=>I('enrol_year',''),
				'student_id'=>I('student_id',''),
				'grade'=>I('grade',''),
				'create_time'=>time(),
			);
			dump($entity);
//			$result = M('students')->add($entity);
//			
//			if(!$result['status']){
//				$this->error($result['info']);
//			}
//
//			$this->success("操作成功！",U('Admin/Students/index',array('parent'=>$part)));
		}
		
	}
	public function edit(){
		$id = I('get.id',0);
		
		if(IS_GET){
			$part=I('parent','');
			$map = array();
			$uid=session('uid',"");
			if(!empty($uid)){
				$map['member_uid'] = $uid;
			}
			
			$result=apiCall('Admin/OrgMember/queryNoPaging',array($map));
			
			if($result['status']){
				$a=$result['info'][0]['organization_id'];
				unset($map);
				$map=" path like '%$a%'";
				$results = apiCall('Admin/Organization/queryNoPaging',array($map));
				if($result['status']){
					$this->assign('list',$results['info']);
					$this->display();
				}else{
					LogRecord('INFO:'.$result['info'],'[FILE] '.__FILE__.' [LINE] '.__LINE__);
					$this->error(L('UNKNOWN_ERR'));
				}
			}
		}else{
			
			$entity = array(
					'id'=>$id,
					'name'=>I('name',''),
					'sex'=>I('sex',''),
					'age'=>I('age',''),
					'enrol_year'=>I('enrol_year',''),
					'student_id'=>I('student_id',''),
					'grade'=>I('grade',''),
					
			);
			$result = M('students')->save($entity);
			if(!$result['status']){
				$this->error($result['info']);
			}
			
			$this->success("操作成功！",U('Admin/Students/index',array('parent'=>$part)));
			
		}
	}
	public function delete(){
		$id = I('get.id',0);
		$result = M('students')->where('id='.$id)->delete();
			
			
			$this->success("操作成功！",U('Admin/Students/index'));
	}
	
}