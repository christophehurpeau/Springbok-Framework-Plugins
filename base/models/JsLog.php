<?php
/** @TableAlias('jl') @TableName('js_log') @Created */
class JsLog extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('bigint(20) unsigned') @NotNull
		*/ $id,
		/** @Boolean
		*/ $is_mobile,
		/** @Boolean
		*/ $is_bot,
		/** @SqlType('varchar(300)') @Null
		*/ $user_agent,
		/** @SqlType('varchar(300)') @Null
		*/ $website,
		/** @SqlType('varchar(300)') @Null
		*/ $location,
		/** @SqlType('varchar(300)') @Null
		*/ $url,
		/** @SqlType('varchar(300)') @Null
		*/ $message,
		/** @SqlType('varchar(10)') @Null
		*/ $line,
		/** @SqlType('tinyint(2) unsigned') @Null
		*/ $platform,
		/** @SqlType('tinyint(2) unsigned') @Null
		*/ $browser,
		/** @SqlType('varchar(20)') @Null
		*/ $version,
		/** @SqlType('varchar(15)') @Null
		*/ $majorver,
		/** @SqlType('varchar(15)') @Null
		*/ $minorver;
		
	public static function create($website,$url,$message,$line){
		$data=CHttpRequest::parseUserAgent();
		$data['is_mobile']=CHttpRequest::isMobile();
		$data['is_bot']=CHttpRequest::isBot();
		$data['message']=$message;
		$data['line']=$line;
		return self::QInsert()->data($data);
	}
}