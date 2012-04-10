<?php
/** @TableAlias('gui') */
class GuestIp extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Guest','id')
		*/ $guest_id,
		/** @SqlType('VARCHAR(39)') @NotNull
		*/ $ip,
		/** @SqlType('DATETIME') @NotNull
		*/ $created;
	
	public static function getOrCreate($guestId){
		$ip=CHttpRequest::getClientIP();
		$id=self::QValue()->field('id')->where(array('guest_id'=>$guestId,'ip'=>$ip));
		if($id!==false) return $id;
		return self::QInsert()->set(array('guest_id'=>$guestId,'ip'=>$ip));
	}
	
	public static function create($guestId){
		return self::QInsert()->set(array('guest_id'=>$guestId,'ip'=>CHttpRequest::getClientIP()));
	}
}