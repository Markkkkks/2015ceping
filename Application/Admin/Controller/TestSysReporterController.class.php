<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------


namespace Admin\Controller;

class TestSysReporterController extends AdminController{
	 
	 /**
	 * 报告查看
	 */
	public function view(){
		$id = I('get.id',0);
		$eval_type  = I('get.eval_type','');
		$org_id = I('get.org_id',0);
		
		$map = array(
			'id'=>$id,
		);
		
		$result = apiCall("TSystem/TestevalUserResult/getInfo",array($map));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$resultReport = unserialize($result['info']['result']);
		
		
		$test_id = $result['info']['test_id'];
		$eval_id = $result['info']['eval_id'];
		
		$prev = apiCall("TSystem/TestevalUserResult/prev",array($org_id,$test_id,$eval_id,$id));
		
		if(!$prev['status']){
			$this->error($prev['info']);
		}
		
		$next = apiCall("TSystem/TestevalUserResult/next",array($org_id,$test_id,$eval_id,$id));
		if(!$next['status']){
			$this->error($next['info']);
		}
		
//		dump($id);
//		dump($prev);
//		dump($next);
		
		$this->assign("prev",$prev['info']);
		$this->assign("next",$next['info']);
		$this->assign("eval_type",$eval_type);
		$this->assign("id",$id);
		$this->assign("org_id",$org_id);
		
		
		if($eval_type == \TSystem\Factory\EvalReporterFactory::MBTI){
			
			$this->assign("result",$resultReport);
			$this->assign("vo",$result['info']);
			
		}elseif($eval_type == \TSystem\Factory\EvalReporterFactory::SCL90){
				
			$this->assign("scores",$resultReport['f_score']);	
			$this->assign("desc",$resultReport['desc']);	
			$this->assign("result",$result['info']);
		}
		
		if(!empty($eval_type)){
			if(is_file(MODULE_PATH."/View/default/".CONTROLLER_NAME."/view_$eval_type.html")){
				$this->display("view_".$eval_type);
			}else{
				$this->display();
			}
		}else{
			$this->display();
		}
	}

	/*
	 * 评阅通过，用户可以看到
	 */
	public function pass(){
		$id = I('get.id',0);
		$notes = I('post.notes','');
		
		$saveEntity = array(
			'review'=>\TSystem\Model\TestevalUserResultModel::REVIEW_STATUS_PASS,
			'review_notes'=>$notes,
			'review_time'=>time(),
		);
		
		$result = apiCall("TSystem/TestevalUserResult/saveByID", array($id,$saveEntity));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$this->success("通过成功!");
		
	}
	
	
	
}
	