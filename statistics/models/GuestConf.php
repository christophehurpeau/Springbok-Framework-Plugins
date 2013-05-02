<?php
/** @TableAlias('guc') */
class GuestConf extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @Index
		*/ $guest_id,
		/** @Boolean
		*/ $is_mobile,
		/** @Boolean
		*/ $is_bot,
		/** @SqlType('varchar(300)') @Null
		*/ $user_agent,
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
	
	public static function create($guestId){
		$data=CHttpUserAgent::parseUserAgent();
		$data['guest_id']=$guestId;
		$data['is_mobile']=CHttpUserAgent::isMobileAndNotTablet();
		$data['is_bot']=CHttpUserAgent::isBot();
		return self::QInsert()->data($data);
	}
	
	public static function check($guestId,$id){
		$data=CHttpUserAgent::parseUserAgent();
		$data['id']=$id;
		$data['guest_id']=$guestId;
		if(self::QExist()->where($data)) return false;
		
		unset($data['id']);
		$data['is_mobile']=CHttpUserAgent::isMobileAndNotTablet();
		$data['is_bot']=CHttpUserAgent::isBot();
		return self::QInsert()->data($data);
	}
}