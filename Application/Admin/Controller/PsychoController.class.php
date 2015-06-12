<?php
/**
 * (c) Copyright 2014 hebidu. All Rights Reserved. 
 */

namespace Admin\Controller;
class PsychoController extends AdminController {
	
	public function index(){
		$uid=session('uid',"");
		if(!empty($uid)){
			$map['member_uid'] = $uid;
		}
			
		$result=apiCall('Admin/OrgMember/queryNoPaging',array($map));
		
		if($result['status']){
			$a=$result['info'][0]['organization_id'];
			$map="schoolid=".$a;
			$list=apiCall("Admin/Students/queryNoPaging",array($map));
			$this->assign('list',$list['info']);
//			dump($list);
			$this->display();
		}
	}
	public function add(){
		$id=I('post.stu');
		$map="id=".$id;
		$maps="uid=".$id;
		$re=apiCall('Admin/Psycho/queryNoPaging',array($maps));
		if($re['info']==NULL){
			$result=apiCall('Admin/Students/queryNoPaging',array($map));
			$map=" path like '%$a%' and level>2";
			$list = apiCall('Admin/Organization/queryNoPaging',array($map));
			$this->assign('list',$list['info']);
			$this->assign('result',$result['info']);
	//		dump($result);
	//		dump($list);
			$this->display();
		}else{
			$this->error('此学生已添加过，无需重复添加');
		}
	}
	public function save(){
		$id=I('id');
		$map="id=".$id;
		$result=apiCall('Admin/Students/queryNoPaging',array($map));
		$entity=array(
			'uid'=>$id,
			'name'=>$result['info'][0]['name'],
			'grade'=>$result['info'][0]['grade'],
			'class'=>$result['info'][0]['class'],
			'sex'=>$result['info'][0]['sex'],
			'date'=>I('post.date'),
			'ethnic'=>I('post.minzu'),
			'origin'=>I('post.jiguan'),
			'bloodtype'=>I('post.blood'),
			'health'=>I('post.jkzk'),
			'address'=>I('post.address'),
			'jz_address'=>I('post.jzdz'),
			'physical'=>I('post.slqx'),
			'likee'=>I('post.like'),
			'speciality'=>I('post.grtc'),
		);
		$results = apiCall("Admin/Psycho/add", array($entity));
		if(!$result['status']){
				$this->error($result['info']);
			}
		$this->success("添加成功！",U('Admin/Psycho/index'));
	}
	public function add2(){
		$this->display();
	}
}