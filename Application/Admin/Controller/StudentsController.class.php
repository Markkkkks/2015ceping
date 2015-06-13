<?php
/**
 * (c) Copyright 2014 hebidu. All Rights Reserved. 
 */

namespace Admin\Controller;

class StudentsController extends AdminController {

	//首页
    public function index(){
       $map="status=0";
       $page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
       $list=apiCall("Admin/Students/query",array($page,$map));
	   $mapp=" level>2 ";
	   $resultt= apiCall('Admin/Organization/queryNoPaging',array($mapp));
	  
	   $this->assign('result',$resultt['info']);
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
				$map=" path like '%$a%' and level=3";
				$mapmap="id=".$a;
				$list=apiCall('Admin/Organization/queryNoPaging',array($mapmap));
				$this->assign('lists',$list['info']);
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
			
			$uid=session('uid');
			$entity = array(
				'uid'=>$uid,
				'name'=>I('name',''),
				'sex'=>I('sex',''),
				'age'=>I('age',''),
				'enrol_year'=>I('enrol_year',''),
				'student_id'=>I('student_id',''),
				'grade'=>I('nj',''),
				'create_time'=>time(),
				'class'=>I('class',''),
				'status'=>0,
				'spe_status'=>0,
				'schoolid'=>I('school',''),
			);
			
				$username=I('student_id','');
				$password=str_replace("-", '', date('Y-m-d',I('date','')));
				
				$email=$username.'@school.com';
				$mobile=I('phone','');
			$user=array(
				
				'nickname'=>I('student_id',''),
				'birthday'=>I('date',''),
				'sex'=>I('sex',''),
				'status'=>1,
				'realname'=>'',
				'idnumber'=>'',
			);
			
			$results = apiCall("Admin/Students/add", array($entity));
			
		if($results['status']){
			$result = apiCall("Uclient/User/register", array($username, $password, $email,$mobile));
//			dump($result);
			if($result['status']){
				$resultss = apiCall("Admin/Member/add", array($user));
//				
				if($resultss['status']){
					$this->success("操作成功！",U('Admin/Students/index',array('parent'=>$part)));
				}
				
			}

		}
		}
		
	}
	public function edit(){
		$id = I('get.id',0);
		
		if(IS_GET){
			$map="id=".$id;
			$list=apiCall("Admin/Students/queryNoPaging",array($map));
			$map=" path like '%$a%' and level>2";
			$results = apiCall('Admin/Organization/queryNoPaging',array($map));
			$this->assign('list',$list['info']);
			$this->assign('entity',$results['info']);
//			dump($results);
			$this->display();
		}else{
			
//			dump($id);
			$entity = array(
					'name'=>I('name',''),
					'sex'=>I('sex',''),
					'age'=>I('age',''),
					'enrol_year'=>I('enrol_year',''),
					'student_id'=>I('student_id',''),
					'grade'=>I('nj',''),
					'class'=>I('class',''),
					
			);
//			dump($entity);
			$result = apiCall('Admin/Students/saveByID',array($id,$entity));
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