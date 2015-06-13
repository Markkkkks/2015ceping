<?php
/**
 * (c) Copyright 2014 hebidu. All Rights Reserved. 
 */

namespace Admin\Controller;
class PsychoController extends AdminController {
	
	public function index(){
	   $page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
       $list=apiCall("Admin/Psycho/query",array($page));
	   $mapp=" level>2 ";
	   $resultt= apiCall('Admin/Organization/queryNoPaging',array($mapp));
	  
	   $this->assign('result',$resultt['info']);
	   $this->assign('list',$list['info']['list']);
	   $this->assign('show',$list['info']['show']);
//	   dump($list);
	   $this->display();
	}
	public function sousuo(){
		
		$name=I('stu_name');
		
		$stuid=I('stu_id');
		$grade=I('nj');
		$cs=I('class');
//		$t1='';$t2='';$t3='';$t4='';$t5='';
		$map=array();
		if(!empty($name)){
			$map['name']=array('like',"%$name%");
		}
		if(!empty($stuid)){
			$map['student_id']="$stuid";
		}
		if(!empty($grade)){
			$map['grade']="$grade";
		}
		if(!empty($cs)){
			$map['class']="$cs";
		}
//		dump($map);
		$mapp=" path like '%$a%' and level=3";
	   	$resultt= apiCall('Admin/Organization/queryNoPaging',array($mapp));
		$page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
	    $mappp=" path like '%$a%'";
	   	$re= apiCall('Admin/Organization/queryNoPaging',array($mappp));
        $list=apiCall("Admin/Students/query",array($map,$page));
        $this->assign('result',$resultt['info']);
		$this->assign('re',$re['info']);
	   	$this->assign('list',$list['info']['list']);
	    $this->assign('show',$list['info']['show']);
//		dump($list);
	    $this->display('toadd');
	}
	public function toadd(){
		$uid=session('uid',"");
		if(!empty($uid)){
			$map['member_uid'] = $uid;
		}
			
		$result=apiCall('Admin/OrgMember/queryNoPaging',array($map));
		
		if($result['status']){
			$a=$result['info'][0]['organization_id'];
			$map="schoolid=".$a;
			$list=apiCall("Admin/Students/query",array($map));
			
			$mapp=" path like '%$a%' and level=3";
	   		$resultt= apiCall('Admin/Organization/queryNoPaging',array($mapp));
			$mappp=" path like '%$a%'";
	   		$re= apiCall('Admin/Organization/queryNoPaging',array($mappp));
			$this->assign('result',$resultt['info']);
			$this->assign('re',$re['info']);
	   		$this->assign('list',$list['info']['list']);
	 	    $this->assign('show',$list['info']['show']);
			$this->display();
		}
	}
	public function add(){
		$id=I('id');
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
	public function add3(){
		$this->display();
	}
	public function select(){
//		dump(''$var'');
		$nj = I("nj_id"); 
		if(isset($nj)){ 
			$map=" father=".$nj;
			$results = apiCall('Admin/Organization/queryNoPaging',array($map));
//			dump($results['info']);
			foreach ($results['info'] as $key) {
				$select[] = array(
					"id"=>$key['id'],
					"orgname"=>$key['orgname'],
					);
//				dump($select);
			}
//
		$this->ajaxReturn($select,'json');
	} 	
	}
}