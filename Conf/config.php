<?php
return array(
	//'配置项'=>'配置值'
    
    //数据库配置
    'DB_DSN' => 'mysql://uF5eK6OmqDlTP:pU3px3iznCeWG@10.4.3.92:3306/d5e4416448bbd464ba1cbef51c2b4ff1a',
    'URL_MODEL' => 2,
    'DEFAULT_THEME' => 'bootstrap',
	
    'TMPL_PARSE_STRING'  =>array(
        '__UPLOAD__' => '/Uploads',
        '__GROUP__' => '/Tpl/bootstrap',
    ),
    
    'TOKEN_ON'=>false,  // 是否开启令牌验证
	'TOKEN_NAME'=>'_hash_',    // 令牌验证的表单隐藏字段名称
	'TOKEN_TYPE'=>'md5',  //令牌哈希验证规则 默认为MD5
	'TOKEN_RESET'=>true,  //令牌验证出错后是否重置令牌 默认为true
    
    //页面压缩
    'TMPL_STRIP_SPACE'      => false,
    'SHOW_ERROR_MAG' => false,
    
    //过滤和验证
    'VAR_FILTERS'=>'stripslashes,strip_tags',
    'DB_FIELDTYPE_CHECK'=>true,
    //表前缀
    'DB_PREFIX' => 'xm_',
    //session
    'SESSION_AUTO_START' =>true,
    //SMTP
    'MAIL_USERNAME'   =>  '小蜜蜂兼职',
    'MAIL_ADDRESS'    =>  'no-reply@xiaomifengjob.com', // 邮箱地址  
	'MAIL_LOGINNAME'  =>  'no-reply@xiaomifengjob.com', // 邮箱登录帐号
	'MAIL_SMTP'       =>  'smtp.ym.163.com', // 邮箱SMTP服务器
	'MAIL_PASSWORD'   =>  'xmfroot2015', // 邮箱密码
    
    //TRACE
	//'SHOW_PAGE_TRACE' =>  true,
	
	//认证执照和身份证照片上传路径
	'UPLOAD_AUTH_PATH' => '/Uploads/auth', 
);
?>