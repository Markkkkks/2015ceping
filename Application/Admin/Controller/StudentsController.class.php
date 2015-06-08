<?php
/**
 * (c) Copyright 2014 hebidu. All Rights Reserved. 
 */

namespace Admin\Controller;

class StudentsController extends AdminController {

	//首页
    public function index(){
      
       $list=M('students')->select();
	   $this->assign('list',$list);
	   $this->display();
    }
	
	public function add(){
		if(IS_GET){
			$uid=session('uid');
			$we='member_uid='.$uid;
			$id=M('org_member','common_')->where($where)->getField('organization_id');
			$where="father=".$id." and level=3";
	//		dump($where);
			$list=M('organization','common_')->where($where)->select();
			$this->assign('list',$list);
			$this->display();
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
			$result = M('students')->add($entity);
			
			if(!$result['status']){
				$this->error($result['info']);
			}

			$this->success("操作成功！",U('Admin/Students/index',array('parent'=>$part)));
		}
		
	}
	public function edit(){
		$id = I('get.id',0);
		
		if(IS_GET){
			$entity =M('students')->where('id='.$id)->select();
			$uid=session('uid');
			$we='member_uid='.$uid;
			$ids=M('org_member','common_')->where($where)->getField('organization_id');
			$where="father=".$ids." and level=3";
	//		dump($where);
			$list=M('organization','common_')->where($where)->select();
			$this->assign('entity',$entity);
			$this->assign('list',$list);
//			dump($entity);
//			dump($list);
			$this->display();
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