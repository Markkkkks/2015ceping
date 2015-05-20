<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------

namespace Home\Controller;

/**
 * 测评控制器
 */
class TestSysController extends HomeController{
	
	protected function _initialize(){
		parent::_initialize();
		$uid = is_login();
		if($uid > 0){
			
			//定义UID常量
			defined("UID") or define("UID",session("uid"));
			
			$this->assign("login_userinfo",session("global_user"));
			
		}else{
			$this->error("请重新登录!",U('Home/Index/logout'));
		}
	}
	
	
	
	/**
	 * 测评中心
	 */
	public function index(){
		
		$orgids = '';//当前用户所属的组织机构
		$orgnames = "";
		$result = apiCall("Admin/OrgMemberView/queryNoPaging", array(array("member_id"=>UID)));
		if(!$result['status']){
			$this->error($result['info']);
		}
		foreach($result['info'] as $vo){
			$orgids .= $vo['orgid'].',';
			$orgnames .= $vo['orgname'].';';
		}
		
		//查询测评记录
		$result = apiCall("TSystem/TestSys/queryWithOrg", array($orgids));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$testlist = $result['info'];
		$eval_ids = '';
		
		foreach($testlist as $vo){
			$eval_ids .= $vo['eval_ids'];			
		}
		
		//TODO: 查询量表记录
		//注意效率问题
		//查询测评记录
		$result = apiCall("TSystem/Evaluation/queryNoPaging", array(array("id"=>array("in",$eval_ids))));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$result_list = array();
		foreach($testlist as $testvo){
			$entity = $testvo;
			$flag = false;
			foreach($result['info'] as $vo){
//				dump(strpos($vo['id'].',', $testvo['eval_ids']));
				if(!(strpos($testvo['eval_ids'],$vo['id'].',') === false)){
					$entity['_eval'] = $vo;
					$flag = true;
					array_push($result_list,$entity);
				}
				
			}
			
			if(!$flag){
				$entity['_eval'] = array('title'=>'','id'=>0);
				array_push($result_list,$entity);
			}
		}
		
		
		
//		dump($result_list);
		//TODO: 查询已做过的测评-量表记录		
		$this->assign("orgnames",$orgnames);
		$this->assign("list",$result_list);
		$this->display();
	}
	
	
	
	/**
	 * 单个测评开始进行测评
	 */
	public function answer(){
		//测评ID
		$test_id = I('get.test_id',0);
		//量表ID
		$eval_id = I('get.eval_id',0);
		//TODO: 不同的量表展示不同的
		
		$this->display();
	}
	
	/**
	 * 测评结果
	 */
	public function result(){
		if(IS_AJAX){
			$uid = UID;
			$testid = I('post.testid',0);
			$evalid = I('post.evalid',0);
			
			$type = \TSystem\Facade\EvalReporterFacade::SCL90;
			
			$result = \TSystem\Facade\EvalReporterFacade::generate(\TSystem\Facade\EvalReporterFacade::MBTI,34,0,1);
			dump($result);
			
			
			$this->assign("time",date("Y-m-d H:i:s",time()));	
			$this->display("result_".$type);
			
		}else{
			$this->error("不支持的方法调用!");
		}
	}
	
	/**
	 * 修改密码
	 */
	public function modifyPwd(){
		if(IS_GET){
			$this->display();
		}else{
			 //获取参数
	        $password   =   I('post.oldpassword');
	        empty($password) && $this->error("当前密码错误,无法修改!");
	        $data['password'] = I('post.password');
	        empty($data['password']) && $this->error("新密码不能为空!");
	        $repassword = I('post.repassword');
	        empty($repassword) && $this->error(('新密码不能为空'));
			
	        if($data['password'] !== $repassword){
	            $this->error("2次输入密码不一致!");
	        }
	        $res    = apiCall('Uclient/User/updateInfo',array(UID, $password, $data));
			
	        if($res['status']){
	            $this->success("修改成功,请重新登录!",U("Home/Index/logout"));
	        }else{
	            $this->error($res['info']);
	        }
		}
	}
	
}
