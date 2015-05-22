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
		
		$map = array(
			'id'=>$id,
		);
		
		$result = apiCall("TSystem/TestevalUserResult/getInfo",array($map));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$resultReport = unserialize($result['info']['result']);
		
		$this->assign("scores",$resultReport['f_score']);	
		$this->assign("desc",$resultReport['desc']);	
		$this->assign("result",$result['info']);
		
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
	
	
}
	