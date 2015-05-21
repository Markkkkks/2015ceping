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
		$test_ids = array();
		$result_list = array();
		$doneEval_ids = array();//已经做过的量表的记录ID,key是测评ID，value是测评中做过的量表ID
		
		foreach($testlist as $testvo){
			$entity = $testvo;
			$doneEval_ids[$testvo['id']] = "";
			array_push($test_ids,$testvo['id']);
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
		$map = array(
			'user_id'=>UID,
		);
		
		foreach($test_ids as $vo){
			$map['test_id'] = $vo;		
			$result = apiCall("TSystem/TestevalUserAnswer/queryNoPaging", array($map));
			
			if(!$result['status']){
				
				$this->error($result['info']);
			}

			if(is_array($result['info'])){
				foreach($result['info'] as $ansVo){
					$doneEval_ids[$vo['id']] .= $ansVo['eval_id'].',';
				}
			}
			
		}
		
//		dump($doneEval_ids);
		
		$this->assign("doneEval_ids",$doneEval_ids);
		$this->assign("orgnames",$orgnames);
		$this->assign("list",$result_list);
		$this->display();
	}
	
	public function preview(){
		
		//测评ID
		$test_id = I('get.test_id',0);
		//量表ID
		$eval_id = I('get.eval_id',0,'intval');
		//TODO: 不同的量表展示不同的
		$eval_type = I('get.eval_type','');
		
		$result = apiCall("TSystem/Evaluation/getInfo", array(array("id"=>$eval_id)));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		$evaluation = $result['info'];
		$result = apiCall("TSystem/TestSys/getInfo", array(array("id"=>$test_id)));
		
		
		
		$this->assign("test",$result['info']);		
		$this->assign("evaluation",$evaluation);
		$this->assign("test_id",$test_id);
		$this->assign("eval_id",$eval_id);
		$this->assign("eval_type",$eval_type);
		$this->display();		
	}
	
	/**
	 * 单个测评开始进行测评
	 */
	public function answer(){
		//测评ID
		$test_id = I('get.test_id',0);
		//量表ID
		$eval_id = I('get.eval_id',0,'intval');
		//TODO: 不同的量表展示不同的
		$eval_type = I('get.eval_type','');
				
		$result = apiCall("TSystem/EvalProblem/queryWithAnswer", array($eval_id));
		
		if(!$result['status']){
			$this->error($result['info']);
		}else{
			
		}
		
		$problems = $this->formatProblems($result['info']);
		
		
		$result = apiCall("TSystem/Evaluation/getInfo", array(array("id"=>$eval_id)));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$evaluation = $result['info'];
		$result = apiCall("TSystem/TestSys/getInfo", array(array("id"=>$test_id)));
		
		
		
		$this->assign("test",$result['info']);		
		$this->assign("evaluation",$evaluation);		
		$this->assign("test_id",$test_id);
		$this->assign("eval_id",$eval_id);
		$this->assign("eval_type",$eval_type);
		$this->assign("problems",$problems);
		
		if(!empty($eval_type)){
			if(is_file(MODULE_PATH."/View/".$this->theme."/".CONTROLLER_NAME."/answer_$eval_type.html")){
				$this->display("answer_".$eval_type);
			}else{
				$this->display();
			}
		}else{
			$this->display();
		}
	}
	
	/**
	 * 答题结果提交
	 */
	public function submit(){
		$uid = UID;
		$testid = I('get.test_id',0);
		$evalid = I('get.eval_id',0);
		$eval_type =  I('get.eval_type','');
		$p_arr = I('post.p',array());
		$psort_arr = I('post.psort',array());
		$cost_time = I('post.elapse_time',0,'intval');
		$ans_arr = array();
		foreach($p_arr as $key=>$vo){
			$ans = I('post.ans_'.$vo,-1);
			//序号_ID_隐藏值
			
			if($ans === -1){
				$this->error("请重新答题!");
			}
			
			list($sort,$id,$hidden_value) = explode("_", $ans);
			
			$ans_arr[] = array('ans'=>array('sort'=>$sort,'id'=>$id,'hidden_value'=>$hidden_value),'p_id'=>$vo,'p_sort'=>$psort);
			
		}
		
		if(count($ans_arr) === 0 || count($ans_arr) != count($p_arr)){
			$this->error("请重新答题!");
		}
		
		
		$ip = get_client_ip();
		$longip = ip2long($ip);
		
		$entity = array(
			'eval_id'=>$evalid,
			'eval_type'=>$eval_type,
			'user_id'=>$uid,
			'test_id'=>$testid,
			'answer'=>serialize($ans_arr),
			'create_time'=>time(),
			'cost_time'=>$cost_time,
			'submit_ip'=>$longip,
		);
		//TODO: 提交前进行检测，是否已提交过
		$result = apiCall("TSystem/TestevalUserAnswer/add", array($entity));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		$params = array('id'=>$result['info']);
		//监听测评提交标签
		tag('test_submit_tag',$params);
		$this->success("提交成功,请等候审核!",U("Home/TestSys/index"));
		
	}
	
	/**
	 * 测评报告历史
	 */
	public function histroyReport(){
		$this->error("TODO: 测评报告历史");
	}
	
	
	/**
	 * 测评报告生成
	 */
//	public function report(){
//		if(IS_AJAX){
//			$uid = UID;
//			$testid = I('post.testid',0);
//			$evalid = I('post.evalid',0);
//			
//			$type = \TSystem\Facade\EvalReporterFacade::SCL90;
//			
//			$result = \TSystem\Facade\EvalReporterFacade::generate(\TSystem\Facade\EvalReporterFacade::MBTI,34,0,1);
//			dump($result);
//			
//			$this->assign("time",date("Y-m-d H:i:s",time()));	
//			$this->display("result_".$type);
//			
//		}else{
//			$this->error("不支持的方法调用!");
//		}
//	}
	
	
	
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
    
	private function formatProblems($problems){
		$result = array();
		$last_id = 0;
		foreach($problems as $vo){
			$last_id = $vo['id'];
			if(!isset($result['p_'.$vo['id']])){
				
				$result['p_'.$vo['id']]  = array(
					'id'=>$vo['id'],
					'title'=>$vo['title'],
					'desc'=>$vo['desc'],
					'sort'=>$vo['sort'],
					'_answers'=>array(),
					'_is_last'=>0,
				);
			}
			
			$result['p_'.$vo['id']]['_answers']['a_'.$vo['ea_id']] = array(
					'id'=>$vo['ea_id'],
				'sort'=>$vo['ea_sort'],
				'title'=>$vo['ea_title'],
				'hidden_value'=>$vo['ea_hidden_value'],
				'icon_url'=>$vo['ea_icon_url'],
				'explain'=>$vo['ea_explain'],
			);
		}
		
		if(count($result)>0){
			$result['p_'.$last_id]['_is_last'] = 1;
		}
		
		return $result;
	}
	
}
