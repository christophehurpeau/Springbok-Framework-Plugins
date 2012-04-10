<?php
/** @TableAlias('gue') */
class Guest extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('DATETIME') @NotNull
		*/ $created,
		/** @SqlType('DATETIME') @Null
		*/ $updated;
		
	public static function getId(){
		return !empty($_COOKIE['guest']) && ($cvalue=(int)USecure::decryptAES($_COOKIE['guest'])) && Guest::existById($cvalue) ? $cvalue : false;
	}
	public static function get(){
		return !empty($_COOKIE['guest']) && ($cvalue=(int)USecure::decryptAES($_COOKIE['guest'])) ? Guest::QOne()->fields('id,pseudo,email')->byId($cvalue)->execute() : false;
	}
	
	public static function getConfId($guestId){
		return !empty($_COOKIE['guest_conf']) && ($cvalue=(int)USecure::decryptAES($_COOKIE['guest_conf'])) && GuestConf::existByIdAndGuest_id($cvalue,$guestId) ? $cvalue : false;
	}
	
	
	public static function getOrCreate(){
		if($cvalue=self::getId()){
			$guest=new Guest;
			$guest->id=$cvalue;
			$result=$guest->update();
			$cvalue=$_COOKIE['guest']; // no need to reencrypt data
		}else{
			$guest=new Guest;
			$result=$guest->insert();
			$cvalue=USecure::encryptAES($result['gid']);
		}
		setcookie('guest',$cvalue,time()+(60*60*24*30*12),'/','',false,true);
		return $result;
	}
	
	public function insert(){
		parent::insert();
		$gcid=GuestConf::create($this->id);
		$giid=GuestIp::create($this->id);
		GuestRequest::create($this->id,$gcid,$giid);
		return array('gid'=>$this->id,'gcid'=>$gcid,'giid'=>$giid);
	}
	public function update(){
		$cookieGcid=self::getConfId($this->id);
		if($cookieGcid===false){
			$gcid=GuestConf::create($this->id);
			setcookie('guest_conf',USecure::encryptAES($gcid),time()+(60*60*24*30*12),'/','',false,true);
		}else{
			$gcid=GuestConf::check($this->id,$cookieGcid);
			setcookie('guest_conf',$gcid===false ? $_COOKIE['guest_conf'] : USecure::encryptAES($gcid),time()+(60*60*24*30*12),'/','',false,true);
			if($gcid===false) $gcid=$cookieGcid;
		}
		
		$giid=GuestIp::getOrCreate($this->id);
		parent::update();
		GuestRequest::create($this->id,$gcid,$giid);
		return array('gid'=>$this->id,'gcid'=>$gcid,'giid'=>$giid);
	}
}