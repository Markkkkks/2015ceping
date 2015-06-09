<?php
/**
 * (c) Copyright 2014 hebidu. All Rights Reserved. 
 */
 

return array(
	'LOAD_EXT_CONFIG' => 'datatree,auth', 
	//唯一管理员用户配置	
   'USER_ADMINISTRATOR' => 1, //管理员用户ID
   'MODULE_DENY_LIST'      =>  array('Common','Runtime','Ucenter','Uclient'),
   'URL_CASE_INSENSITIVE' =>false,
	// 程序版本
	// DONE:移到数据库中
	// 显示运行时间
	'SHOW_RUN_TIME'=>true,
//	'SHOW_ADV_TIME'=>true,
	// 显示数据库操作次数
	'SHOW_DB_TIMES'=>true,
	// 显示操作缓存次数
//	'SHOW_CACHE_TIMES'=>true,
	// 显示使用内存
//	'SHOW_USE_MEM'=>true,
	// 显示调用函数次数
//	'SHOW_FUN_TIMES'=>true,
	// 伪静态配置
	'URL_HTML_SUFFIX'=>'shtml'	,
    // 路由配置
    'URL_MODEL'                 =>  1, // 如果你的环境不支持PATHINFO 请设置为3
    // 数据库配置
    'DB_TYPE'                   =>  'mysql',
    'DB_HOST'                   =>  '192.168.0.100',//rdsrrbifmrrbifm.mysql.rds.aliyuncs.com
    'DB_NAME'                   =>  'boye_2015_05_26_14_29_19', //boye_ceping
    'DB_USER'                   =>  'root',//boye
    'DB_PWD'                    =>  '1',//bo-ye2015BO-YE
    'DB_PORT'                   =>  '3306',
    'DB_PREFIX'                 =>  'itboye_',
    
    
	
   //调试
    'LOG_RECORD' => true, // 开启日志记录
    'LOG_TYPE'              =>  'Db',
	'LOG_LEVEL'  =>'EMERG,ALERT,CRIT,ERR', // 只记录EMERG ALERT CRIT ERR 错误
    'LOG_DB_CONFIG'=>array(
<<<<<<< HEAD
		'dsn'=>'mysql://boye105:1@192.168.0.100:3306/boye_2015_05_26_14_29_19' //本地日志数据库
=======
		'dsn'=>'mysql://root:1@192.168.0.100:3306/boye_2015_05_26_14_29_19' //本地日志数据库
>>>>>>> branch 'master' of https://github.com/h136799711/2015ceping.git
	),
	
    // Session 配置
    'SESSION_PREFIX' => 'oauth_',
    
    //权限配置
    'AUTH_CONFIG'=>array(
        'AUTH_ON' => true, //认证开关
        'AUTH_TYPE' => 1, // 认证方式，1为时时认证；2为登录认证。
        'AUTH_GROUP' => 'common_auth_group', //用户组数据表名
        'AUTH_GROUP_ACCESS' => 'common_auth_group_access', //用户组明细表
        'AUTH_RULE' => 'common_auth_rule', //权限规则表
        'AUTH_USER' => 'common_members'//用户信息表
    )
		
);



