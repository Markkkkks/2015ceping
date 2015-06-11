<?php
/**
 * (c) Copyright 2014 hebidu. All Rights Reserved. 
 */

namespace Admin\Controller;

class TeacherController extends AdminController {

	//首页
    public function index(){
       $page = array('curpage' => I('get.p', 0), 'size' => C('LIST_ROWS'));
       $list=apiCall("Admin/Teachers/query",array($page));
//	 dump($list);
	   if(!$list['status']){
	   	 $this->error($result['info']);
	   }
//	   dump($list);
	   $map='level=2';
	   $result=apiCall('Admin/Organization/queryNoPaging',array($map));
	 
	   $this->assign('list',$list['info']['list']);
	   $this->assign('show',$list['info']['show']);
	   $this->assign('result',$result['info']);
	   $this->display();
    }
	public function add(){
		$uid=session('uid');
		if(IS_GET){
			$map=array();
			if(!empty($uid)){
				$map['member_uid'] = $uid;
			}
			$result=apiCall('Admin/OrgMember/queryNoPaging',array($map));
			
			if($result['status']){
				$a=$result['info'][0]['organization_id'];
				unset($map);
				$mapmap="id=".$a;
				$list=apiCall('Admin/Organization/queryNoPaging',array($mapmap));
				if($result['status']){
//					dump($list);
					$this->assign('list',$list['info']);
					$this->display();
				}else{
					LogRecord('INFO:'.$result['info'],'[FILE] '.__FILE__.' [LINE] '.__LINE__);
					$this->error(L('UNKNOWN_ERR'));
				}
			}
		}else{
			$entity=array(
				'name'=>I('name',''),
				'sex'=>I('sex',''),
				'uid'=>$uid,
				'employee_time'=>I('post.employee_time',''),
				'employee_id'=>I('tea_id',''),
				'school_id'=>I('school',''),
				'notes'=>I('notes',''),
				'create_time'=>time(),
			);
				$username=I('tea_id','');
				$password=str_replace("-", '', date('Y-m-d',I('date','')));
				
				$email=$username.'@teacher.com';
				$mobile=I('phone','');
			$user=array(
				
				'nickname'=>I('tea_id',''),
				'birthday'=>I('date',''),
				'sex'=>I('sex',''),
				'status'=>1,
				'realname'=>'',
				'idnumber'=>'',
			);
//			dump($user);dump($username);dump($password);dump($email);dump($mobile);
			$results = apiCall("Admin/Teachers/add", array($entity));
			
			if($results['status']){
				$result = apiCall("Uclient/User/register", array($username, $password, $email,$mobile));
	//			dump($result);
				if($result['status']){
					$resultss = apiCall("Admin/Member/add", array($user));
	//				
					if($resultss['status']){
						$this->success("操作成功！",U('Admin/Teacher/index',array('parent'=>$part)));
					}
				}
			}
			
		}
	}
	public function edit(){
		if(IS_GET){
			$id=I('get.id','');
			$map='id='.$id;
			$result=apiCall("Admin/Teachers/queryNoPaging", array($map));
			$this->assign('list',$result['info']);
			$maps='level=2';
	 	   $map=array();
			if(!empty($uid)){
				$map['member_uid'] = $uid;
			}
			$result=apiCall('Admin/OrgMember/queryNoPaging',array($map));
			
			if($result['status']){
				$a=$result['info'][0]['organization_id'];
				unset($map);
				$mapmap="id=".$a;
				$lists=apiCall('Admin/Organization/queryNoPaging',array($mapmap));
				if($result['status']){
//					dump($list);
					$this->assign('lists',$lists['info']);
					$this->display();
				}else{
					LogRecord('INFO:'.$result['info'],'[FILE] '.__FILE__.' [LINE] '.__LINE__);
					$this->error(L('UNKNOWN_ERR'));
				}
			}
//			dump($results);
			
		}else{
			$id=I('get.id','');
			$entity=array(
				'name'=>I('name',''),
				'sex'=>I('sex',''),
				'employee_time'=>I('employee_time',''),
				'employee_id'=>I('tea_id',''),
				'school_id'=>I('school',''),
				'notes'=>I('notes',''),
			);
//			dump($entity);
			$results = apiCall("Admin/Teachers/saveByID", array($id,$entity));
			if(!$result['status']){
					
				}
				$this->success("操作成功！",U('Admin/Teacher/index',array('parent'=>$part)));
		}
	}

	public function del(){
		
		$id = I('get.id','');
		$map='id='.$id;
//		dump($id);
		$result = apiCall("Admin/Teachers/delete", array($map));
//	dump($result);
		if(!$result['status']){
			$this->error($result['info']);
		}
			$this->success("删除成功！");
	}
}