<?php
/* Inspired by SpringbokPlugin Statistics */
/** @TableAlias('gue') */
class Guest extends SSqlModel{
	CONST UNKNOWN=0,DEFINED=1,GENERATED=2;
	
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('varchar(40)') @Null
		*/ $pseudo,
		/** @SqlType('tinyint(1) unsigned') @NotNull @Default(1)
		*/ $pseudo_type,
		/** @SqlType('varchar(120)') @NotNull
		*/ $email,
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
		$giid=GuestIp::create($this->id);
		return array('gid'=>$this->id,'giid'=>$giid);
	}
	public function update(){
		$giid=GuestIp::getOrCreate($this->id);
		parent::update();
		return array('gid'=>$this->id,'giid'=>$giid);
	}
}