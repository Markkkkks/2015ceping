<?php
// .-----------------------------------------------------------------------------------
// | WE TRY THE BEST WAY 杭州博也网络科技有限公司
// |-----------------------------------------------------------------------------------
// | Author: 贝贝 <hebiduhebi@163.com>
// | Copyright (c) 2013-2016, http://www.itboye.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------


namespace TSystem\Api;

class SCL90EvalReporterApi implements IEvaluationReporter{
	/**
	 * 该报告生成对应的量表类型
	 */
	const EVAL_TYPE = 25;
	
	private $answer ;
	private $formatAnswer ;
	
	/**
	 * 数组形式 
	 * @param array $params  array('id'=>'id')
	 */
	public function generate($params){
		
		if(!is_array($params) || !isset($params['id'])){
			trigger_error("缺少参数ID!");
		}
		
		$map = array(
			'id'=>$params['id'],
		);
		
		$result = apiCall("TSystem/TestevalUserAnswer/getInfo", array($map));
		if(!$result['status']){
			return $this->returnErr($result['info']);
		}
		$entity = $result['info'];//取得用户选择的答案
		$this->answer = unserialize($entity['answer']);
		//答案需要包含信息
		//1. 问题编号
		//2. 答案编号，答案隐藏值,
		//========================进行SCL90量表的分析并生成相应数据======
		$result = array();
		$this->format();
//		dump($this->answer);
//		dump($this->formatAnswer);
		//======================F1-F10的得分与总因子分数=========
		$f_score = array();
		$f_score[] = $this->getPositiveItemsAndAllScore();
		$f_score[] = $this->getF1();
		$f_score[] = $this->getF2();
		$f_score[] = $this->getF3();
		$f_score[] = $this->getF4();
		$f_score[] = $this->getF5();
		$f_score[] = $this->getF6();
		$f_score[] = $this->getF7();
		$f_score[] = $this->getF8();
		$f_score[] = $this->getF9();
		$f_score[] = $this->getF10();
		
		
//		dump($f_score);
		//================================
		
		$result['f_score'] = $f_score;		
		$result['desc'] = $this->getStaticsReport($f_score);
		dump($result['desc'] );
		//================================
		return $this->returnSuc($result);
	}
	
	
	/**
	 * 获取统计分析报告
	 * @param array $f_score 已处理过的分数数组
	 */
	public function getStaticsReport($f_score){
		$totalScore = $f_score[0]['allscore'];
		$positive_items = $f_score[0]['positive_items'];
		$positive_avg = $f_score[0]['avg'];
//		dump($f_score[0]);
		$desc = array();//总分概要
		$descF110 = array();//F1-F10因子描述
		$summary = array();//总结分析报告
		$notInState = array();//不在状态的因子
		$maxAvg = array();//最高因子
		
		if($totalScore > 160){
			$desc[] = "该测验结果总分 ".$totalScore."，大于160；提示有阳性意义. 受检者自我感觉不在良好状态。";
		}
		if($positive_items > 44 ){
			$desc[] = "该测验结果阳性项目数：".$positive_items."，大于43； 提示有阳性意义. 受检者自我感觉不在良好状态。";
		}
		
		if($positive_avg > 2 ){
			$desc[] = "该测验结果阳性症状均分为 ".$positive_avg."，大于2； 提示有阳性意义. 受检者自我感觉不在良好状态。";
		}
		
		if($totalScore <= 160 && $positive_items <= 44 && $positive_avg <= 2){
			$desc[] = "该测验结果基本在正常范围内。";
		}
		
		if($f_score[1]['avg'] > 2){
			
			$notInState[] = "躯体化" ;
			$maxAvg = array($f_score[1]['avg'],"躯体化");
			$descF110[] = "躯体化：因子分 ".$f_score[1]['avg']." 分，超过2分，提示有阳性意义。";
			$descF110[] = "躯体化因子主要反映被试者的主观的身体不适感，包括心血管、胃肠道、呼吸等系统的不适，及头痛、背痛、肌肉酸痛及焦虑等其他躯体表现。";
		}
		
		if($f_score[2]['avg'] > 2){
			
			$notInState[] = "强迫症状" ;
			
			if($maxAvg[0] < $f_score[2]['avg']){
				$maxAvg = array($f_score[2]['avg'],"强迫症状");
			}
			
			$descF110[] = "强迫症状：因子分 ".$f_score[2]['avg']." 分，超过2分，提示有阳性意义。 ";
			$descF110[] = "强迫症状因子主要指那种明知没有必要，但又无法摆脱的无意义的思想、冲动、行为等表现，还有一些比较一般的感知障碍(如脑子变空了，“记忆力不行”等)也在这一因子中反映。";
		}
		
		if($f_score[3]['avg'] > 2){
			
			$notInState[] = "人际关系敏感" ;
			
			if($maxAvg[0] < $f_score[3]['avg']){
				$maxAvg = array($f_score[3]['avg'],"人际关系敏感");
			}
			$descF110[] = "人际关系敏感：因子分 ".$f_score[3]['avg']." 分，超过2分，提示有阳性意义。 ";
			$descF110[] = "人际关系敏感：该因子主要是反映某些个人不自在感与自卑感，尤其是在与其他人相比较时更为突出。自卑感、懊丧、以及在人事关系明显相处不好的人，往往这一因子得高分。";
		}
		
		
		if($f_score[4]['avg'] > 2){
			
			$notInState[] = "忧郁" ;
			
			if($maxAvg[0] < $f_score[4]['avg']){
				$maxAvg = array($f_score[4]['avg'],"忧郁");
			}
			$descF110[] = "忧郁：因子分 ".$f_score[4]['avg']." 分，超过2分，提示有阳性意义。 ";
			$descF110[] = "忧郁因子：反映的是临床上忧郁症状群相联系的广泛的概念。忧郁苦闷的感情和心境是代表性症状，它还以对生活的兴趣减退，缺乏活动的愿望、丧失活动力等为特征，并包括失望、悲叹、与忧郁相联系的其它感知及躯体方面的问题。";
		}
		
		if($f_score[5]['avg'] > 2){
			
			$notInState[] = "焦虑" ;
			
			if($maxAvg[0] < $f_score[5]['avg']){
				$maxAvg = array($f_score[5]['avg'],"焦虑");
			}
			$descF110[] = "焦虑：因子分 ".$f_score[5]['avg']." 分，超过2分，提示有阳性意义。 ";
			$descF110[] = "焦虑因子：包括一些通常临床上明显与焦虑症状相联系的症状与体验。一般指那些无法静息、神经过敏、紧张、以及由此产生躯体征象 (如震颤) 。那种游离不定的焦虑及惊恐发作是本因子的主要内容，它还包括有一个反映“解体”的项目。";
		}
		
		
		
		if($f_score[6]['avg'] > 2){
			
			$notInState[] = "敌对" ;
			
			if($maxAvg[0] < $f_score[6]['avg']){
				$maxAvg = array($f_score[6]['avg'],"敌对");
			}
			$descF110[] = "敌对：因子分 ".$f_score[6]['avg']." 分，超过2分，提示有阳性意义。 ";
			$descF110[] = "敌对因子：主要以三方面来反映病人的敌对表现、思想、感情及行为。包括从厌烦、争论、摔物、直至争斗和不可抑制的冲动暴发等各个方面。";
		}
		
		
		if($f_score[7]['avg'] > 2){
			
			$notInState[] = "恐怖" ;
			
			if($maxAvg[0] < $f_score[7]['avg']){
				$maxAvg = array($f_score[7]['avg'],"恐怖");
			}
			$descF110[] = "恐怖：因子分 ".$f_score[7]['avg']." 分，超过2分，提示有阳性意义。 ";
			$descF110[] = "恐怖因子：与传统的恐怖状态所反映的内容基本一致，恐惧的对象包括出门旅行，空旷场地、人群、或公共场合及交通工具。此外还有反映社交恐怖的项目。";
		}
		
		
		
		if($f_score[8]['avg'] > 2){
			
			$notInState[] = "偏执" ;
			
			if($maxAvg[0] < $f_score[8]['avg']){
				$maxAvg = array($f_score[8]['avg'],"偏执");
			}
			$descF110[] = "偏执：因子分 ".$f_score[8]['avg']." 分，超过2分，提示有阳性意义。 ";
			$descF110[] = "偏执因子：偏执是一个十分复杂的概念，本因子只是包括了它的一些基本内容，主要是指思维方面，  如投射性思维，敌对、猜疑、关系妄想、忘想、被动体验和夸大等。";
		}
		
		
		if($f_score[9]['avg'] > 2){
			
			$notInState[] = "精神病性" ;
			
			if($maxAvg[0] < $f_score[9]['avg']){
				$maxAvg = array($f_score[9]['avg'],"精神病性");
			}
			$descF110[] = "精神病性：因子分 ".$f_score[9]['avg']." 分，超过2分，提示有阳性意义。 ";
			$descF110[] = "精神病性因子：其中有幻想、思维播散、被控制感、思维被插入等反映精神分裂症择定状项目。";
		}

		
		
		
		if($f_score[10]['avg'] > 2){			
			$notInState[] = "其它" ;
			
			if($maxAvg[0] < $f_score[10]['avg']){
				$maxAvg = array($f_score[10]['avg'],"其它");
			}
			$descF110[] = "其它：因子分 ".$f_score[10]['avg']." 分，超过2分，提示有阳性意义。 ";
			$descF110[] = "其它因子是反映睡眠及饮食情况的。";
		}
		
		if(count($descF110) === 0){
			$summary[] =  "该测验结果基本在正常范围内";
		}else{
			$summary_desc = "其中 ".$maxAvg[1]." 因子分最高,";
			foreach($notInState as $vo){
				$summary_desc .= $vo.',';	
			}
			$summary_desc .= "均不在理想的状态，根据以上分析，建议进一步检查。";
			$summary[] = $summary_desc;
		}
		
		return array("desc"=>$desc,'descF110'=>$descF110,'summary'=>$summary);
	}
	
	
	private function getFscore($findex){
		$total = 0;
		
		foreach($findex as $vo){
			$ans = $this->formatAnswer[$vo]['_ans'];
			$hidden_value = intval($ans['hidden_value']);
			$total = $total + $hidden_value;
		}
		
		$avg = number_format(($total*1.0 / count($findex)),2);
		
		return array('total'=>$total,'avg'=>$avg);
	}
	
	/**
	 * 躯体化
	 * 该因子主要反映主观的身体不适感，包括心血管、
          肠胃道、呼吸道系统主诉不适和头痛、脊痛、肌肉酸痛、以及焦
          虑的其他躯体表现。
	 */
	private function getF1(){
		$f1 = array(1,4,12,27,40,42,48,49,52,53,56,58);
		$result = $this->getFscore($f1);
		return $result;
	}
	
	/**
	 * 强迫
	 * 该因子主要指那种明知没有必要，但又无法摆脱的无
          意义的思想、冲动、行为等表现，还有一些比较一般的感知障碍
          (如脑子变空了，“记忆力不行”等)也在这一因子中反映。
	 */
	private function getF2(){
		$f2 = array(3,9,10,28,38,45,46,51,55,65);
		$result = $this->getFscore($f2);
		return $result;
		
	}
	
	/**
	 * 人际关系敏感
	 * 该因子主要是反映某些个人不自在感与自卑感，
          尤其是在与其他人相比较时更为突出。自卑感、懊丧、以及在人
          事关系明显相处不好的人，往往这一因子得高分。
	 * 
	 */
	private function getF3(){
		$f3 = array(6,21,34,36,37,41,61,69,73);
		$result = $this->getFscore($f3);
		return $result;
		
	}
	
	/**
	 * 抑郁
	 * 反映的是临床上忧郁症状群相联系的广泛的概念。忧
          郁苦闷的感情和心境是代表性症状，它还以对生活的兴趣减退，
          缺乏活动的愿望、丧失活动力等为特征，并包括失望、悲叹、与
          忧郁相联系的其它感知及躯体方面的问题。
	 * 
	 */
	private function getF4(){
		$f4 = array(5,14,15,20,22,26,29,30,31,32,54,71,79);
		$result = $this->getFscore($f4);
		return $result;
		
	}
	
	/**
	 * 焦虑
	 * 包括一些通常临床上明显与焦虑症状相联系的症状与
          体验。一般指那些无法静息、神经过敏、紧张、以及由此产生躯
          体征象 (如震颤) 。那种游离不定的焦虑及惊恐发作是本因子的
          主要内容，它还包括有一个反映“解体”的项目。
	 * 
	 */
	private function getF5(){
		$f5 = array(2,17,23,33,39,57,72,78,80,86	);
		
		$result = $this->getFscore($f5);
		return $result;
	}
	
	/**
	 * 敌意
	 * 主要以三方面来反映病人的敌对表现、思想、感情及
          行为。包括从厌烦、争论、摔物、直至争斗和不可抑制的冲动暴
          发等各个方面。
	 * 
	 */
	private function getF6(){
		$f6 = array(11,24,63,67,74,81);
		$result = $this->getFscore($f6);
		return $result;
		
	}
	
	/**
	 * 恐怖
	 * 与传统的恐怖状态所反映的内容基本一致，恐惧的对
          象包括出门旅行，空旷场地、人群、或公共场合及交通工具。此
          外还有反映社交恐怖的项目。
	 * 
	 */
	private function getF7(){
		$f7 = array(13,25,47,50,70,75,82);
		$result = $this->getFscore($f7);
		return $result;
		
	}
	
	/**
	 * 妄想
	 * 偏执是一个十分复杂的概念，本因子只是包括了它的
          一些基本内容，主要是指思维方面，  如投射性思维，敌对、猜
          疑、关系妄想、忘想、被动体验和夸大等。
	 * 
	 */
	private function getF8(){
		$f8 = array(8,18,43,68,76,83	);
		$result = $this->getFscore($f8);
		return $result;
	}
	
	/**
	 * 精神病性
	 * 其中有幻想、思维播散、被控制感、思维被插入等反
          映精神分裂症择定状项目。
	 * 
	 */
	private function getF9(){
		$f9 = array(7,16,35,62,77,84,85,87,88,90	);
		
		$result = $this->getFscore($f9);
		return $result;
	}
	
	/**
	 * 其他
	 * 该因子是反映睡眠及饮食情况的。
	 * 
	 */
	private function getF10(){
		$f10= array(19,44,59,60,64,66,89);
		$result = $this->getFscore($f10);
		return $result;
	}
	
	/**
	 * 
	 * 
	 * 获得阳性项目数目
	 * 公式 = 90 — 选A的项目数
	 * @return array('allscore'=>'总分','items'=>'阳性项目总数','avg'=>'阳性项目均分')
	 * 
	 */
	private function getPositiveItemsAndAllScore(){
		/**
    		 *	阳性项目数：表示被试在多少项目中呈现“有症状”。
		 *	阳性项目均分：表示“有症状”项目的平均得分。 可以看出被试自我感觉不佳的程度究竟在哪个范围。 
		 *  
		 */
		 $result = array('allscore'=>0,'positive_items'=>0,'avg'=>0.0);
		 
		 foreach($this->formatAnswer as $vo){
		 	
			$ans = $vo['_ans'];
			$id = $ans['id'];
			$hidden_value = intval($ans['hidden_value']);
			if($hidden_value > 1){
				$result['allscore'] += $hidden_value;
				$result['positive_items']++;
			}
			
		}
		
		$result['avg'] = number_format(($result['allscore']*1.0 / $result['positive_items']),2);
		
		return $result;
	}
	
	
	/**
	 * 因子	所 属 因 子 的 项 目 编 号								"累计得分（S）"			"T分数（S/项目数）"
		F1	1,4,12,27,40,42,48,49,52,53,56,58		
		F2	3,9,10,28,38,45,46,51,55,65		
		F3	6,21,34,36,37,41,61,69,73		
		F4	5,14,15,20,22,26,29,30,31,32,54,71,79		
		F5	2,17,23,33,39,57,72,78,80,86		
		F6	11,24,63,67,74,81		
		F7	13,25,47,50,70,75,82		
		F8	8,18,43,68,76,83		
		F9	7,16,35,62,77,84,85,87,88,90		
		F10	19,44,59,60,64,66,89		
		"阳性项目总数：（=90—选A的项目数）"				总累计得分：			总因子分数：
	 * 
	 * 
	 * 
	 * [评分规则]
    选A计1分，选B计2分，选C计3分，选D计4分，选E计5分。
	将因子F1（躯体化）、F2（强迫）、F3（人际关系敏感）、F4（抑郁）、F5（焦虑）、F6（敌意）、F7（恐怖）、F8（妄想）、F9（精神病性）、F10（其他）各自	包含的项目得分分别累计相加，即可得到各个因子的累计得分；
	将各个因子的累计得分除以其相应的项目数，即可得到各个因子的因子分数——T分数。例如，若躯体化一项合计分为8，题目数为8，则因子分为1。
	
	SCL－90主要提供以下分析指标：
    总分和总均分：总分是90个项目各单项得分相加，最低分为90分，最高分为450分。 总均分＝总分÷90，表示总的来看，被试的自我感觉介于1－5的哪一个范围。
    阴性项目数：表示被试“无症状”的项目有多少。
    阳性项目数：表示被试在多少项目中呈现“有症状”。
阳性项目均分：表示“有症状”项目的平均得分。 可以看出被试自我感觉不佳的程度究竟在哪个范围。 
	 * 
	 */
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	private function format(){
		$this->formatAnswer = array();
		foreach($this->answer as $vo){
			$this->formatAnswer[$vo['p_sort']] = array(
				'id'=>$vo['p_id'],
				'_ans'=>$vo['ans']
			);
		}
		
	}
	
	
	
	
	
	
	
	
	private function returnErr($info){
		return array('status'=>false,'info'=>$info);
	}
	
	private function returnSuc($info){
		return array('status'=>true,'info'=>$info);
	}
	
	
	
}
