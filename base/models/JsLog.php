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
		*/ $href,
		/** @SqlType('varchar(300)') @Null
		*/ $url,
		/** @SqlType('varchar(300)') @Null
		*/ $message,
		/** @SqlType('varchar(10)') @Null
		*/ $line,
		/** @SqlType('tinyint(2) unsigned') @Null @Comment("WINDOWS=0,MAC=1,LINUX=2,FREE_BSD=3,IPOD=10,IPAD=11,IPHONE=12,ANDROID=13,SYMBIAN=14,IMODE=15,NINTENDO_WII=20,PLAYSTATION_PORTABLE=21")
		*/ $platform,
		/** @SqlType('tinyint(2) unsigned') @Null @Comment("CRAWLER=0,OPERA_MINI=1,OPERA=2,IE=3,FIREFOX=4,CHROME=5,CHROMIUM=6,SAFARI=7,EPIPHANY=10,FENNEC=11,ICEWEASEL=12,MINEFIELD=13,MINIMO=14,FLOCK=15,FIREBIRD=16,PHOENIX=17,CAMINO=18,CHIMERA=19,THUNDERBIRD=20,NETSCAPE=21,OMNIWEB=22,IRON=23,ICAB=24,KONQUEROR=25,MIDORI=26,DOCOMO=27,LYNX=28,LINKS=29,W3C_VALIDATOR=30,APACHE_BENCH=31,LIBWWW_PERL_LIB=32,W3M=33,WGET=34")
		*/ $browser,
		/** @SqlType('varchar(20)') @Null
		*/ $version,
		/** @SqlType('varchar(15)') @Null
		*/ $majorver,
		/** @SqlType('varchar(15)') @Null
		*/ $minorver;
		
	public static function create($href,$jsurl,$message,$line){
		$data=CHttpRequest::parseUserAgent();
		$data['is_mobile']=CHttpRequest::isMobile();
		$data['is_bot']=CHttpRequest::isBot();
		$data['href']=$href;
		$data['url']=$jsurl;
		$data['message']=$message;
		$data['line']=$line;
		return self::QInsert()->data($data);
	}
}