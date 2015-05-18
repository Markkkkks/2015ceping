<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
namespace Home\Controller;
use Think\Controller;
class IndexController extends HomeController {
	
    public function index(){
    		$map = array('parentid'=>getDatatree("POST_CATEGORY"));
		
		$cates = apiCall("Home/Datatree/queryNoPaging",array($map));
		if(!$cates['status']){
			$this->error($cates['info']);
		}
		
		$this->assign("cates",$cates['info']);
		$this->display();
	} 
	
	public function cate(){
		$cateid = I('get.cateid',0);
		$map = array('post_category'=>$cateid,'post_status'=>'publish');
		
		$result = apiCall("Home/Datatree/getInfo", array(array('id'=>$cateid)));
		
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		if(is_null($result['info'])){
			$this->error("该分类不存在!");
		}
		
		$this->assign("title",$result['info']['name']);
		$page = array('curpage'=>I('get.p',0),'size'=>10);
		
		$result = apiCall("Home/Post/query", array($map,$page));
//		dump($result);
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$this->assign("list",$result['info']['list']);
		$this->assign("show",$result['info']['show']);
		$this->display("list");
		
	}
	
	public function view(){
		$id = I('get.id',0);
		$map = array('id'=>$id);
		$result = apiCall("Home/Post/getInfo", array($map));
		if(!$result['status']){
			$this->error($result['info']);
		}
		
		$content = htmlspecialchars_decode($result['info']['post_content']);
		$title = $result['info']['post_title'];
		$this->assign("post",$result['info']);
		$this->assign("title",$title);
		$this->assign("content",$content);
		
		$this->display();
	}
}

