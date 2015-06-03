<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------


namespace Home\Controller;


class ReporterController extends HomeController{
	 
	 /**
	 * 报告查看
	 */
	public function view(){
		$id = I('get.id',0);
		$eval_type  = I('get.eval_type','');
		$params = array(
			'id'=>$id,
		);
		
		$result = \TSystem\Factory\EvalReporterFactory::getData($eval_type,$id);
//		dump($result);
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$this->assign("data",$result['info']);
		
		
		
		if(!empty($eval_type)){
			
			if(is_file(MODULE_PATH."/View/".$this->theme."/".CONTROLLER_NAME."/view_$eval_type.html")){
				$this->display("view_".$eval_type);
			}else{
				$this->display();
			}
		}else{
			$this->display();
		}
	}

	
//	private function reporterAssign($result,$resultReport){
//		if($eval_type == \TSystem\Factory\EvalReporterFactory::MBTI){
//				
//			$this->assign("result",$resultReport);
//			$this->assign("vo",$result['info']);
//				
//		}elseif($eval_type == \TSystem\Factory\EvalReporterFactory::SCL90){
//			
//			$this->assign("scores",$resultReport['f_score']);	
//			$this->assign("desc",$resultReport['desc']);	
//			$this->assign("result",$result['info']);
//		
//		}else{
//			//其它所有量表
//			
//			$this->assign("result",$resultReport);
//			$this->assign("vo",$result['info']);
//		
//		}
//		
//		//获取智能云平台的方案
//		$solutions = array();
//		
//		//心理症状情况
//		$solutions['title'] = "心理症状情况";
//		$solutions['content'] = "根据症状分析结果显示，您近期存在较重程度的环境适应不良";
//		
//		//咨询方法建议	
//		$solutions['title'] = "咨询方法建议";
//		
//	}
	
	
}
	