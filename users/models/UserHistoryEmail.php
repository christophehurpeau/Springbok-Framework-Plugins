<?php
/** @TableAlias('uhe') @Created @Updated @Index('user_id','email','code','status') */
class UserHistoryEmail extends SSqlModel{
	const WAITING=0,VALID=1,CANCELED=2,CANCELED_VALID=3,RESTORED=4;
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('User','id')
		*/ $user_id,
		/** @SqlType('VARCHAR(120)') @NotNull
		*/ $email,
		/** @SqlType('tinyint(1) unsigned') @NotNull @Default(0)
		*/ $status,
		/** @SqlType('varchar(14)') @Null
		*/ $code,
		/** @Boolean @Default(true) @Comment('last validable operation')
		*/ $last;
	
	public static function create($userId,$email,$valid=false){
		$exist=UserHistoryEmail::QOne()->where(array('user_id'=>$userId,'email'=>$email,'status'=>self::WAITING));
		if($exist!==false) return $exist;
		
		UserHistoryEmail::QUpdateOneField('last',false)->byUser_id($userId);
		$uph=new UserHistoryEmail;
		$uph->user_id=$userId;
		$uph->email=$email;
		if(!$valid) $uph->code=UGenerator::randomCode(14);
		else $uph->status=self::VALID;
		$uph->insert();
		return $uph;
	}
	
	public static function validEmail($userId,$email,$code){
		if($uheId=self::QValue()->field('id')->where(array('user_id'=>$userId,'email'=>$email,'code'=>$code,'status'=>self::WAITING,'last'=>true))){
			self::updateOneFieldByPk($uheId,'status',self::VALID);
			return $uheId;
		}
		return false;
	}
	
	public static function cancelable($userId,$email,$code){
		return self::QOne()->fields('id,status')
			->where(array('user_id'=>$userId,'email'=>$email,'code'=>$code,'status'=>array(self::WAITING,self::VALID),'last'=>true,'ADDDATE(created,INTERVAL 15 DAY) >= CURDATE()'));
	}
	
	public static function lastValid($userId,$except=false){
		$where=array('user_id'=>$userId,'status'=>self::VALID);
		if($except!==false) $where['id !=']=$except;
		return self::QValue()->fields('email')->where($where)->orderBy(array('created'=>'DESC'));
	}
	
	public function details(){
		return $this->email;
	}
}