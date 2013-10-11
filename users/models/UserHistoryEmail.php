<?php
/** @TableAlias('uhe') @Created @Updated @Index('user_id','email','code','status') */
class UserHistoryEmail extends SSqlModel{
	const WAITING=0,VALID=1,CANCELED=2,CANCELED_VALID=3,RESTORED=4,WAITING_LOST_PASSWORD=5,VALID_LOST_PASSWORD=6;
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
	
	public static function create($userId,$email,$valid=false,$status=self::WAITING){
		$exist = UserHistoryEmail::QOne()->where(array('user_id'=>$userId,'email'=>$email,'status'=>$status))->fetch();
		if($exist !== false) return $exist;
		
		UserHistoryEmail::QUpdateOneField('last',false)->byUser_id($userId)->execute();
		$uph=new UserHistoryEmail;
		$uph->user_id = $userId;
		$uph->email = $email;
		if(!$valid){
			$uph->code = UGenerator::randomCode(14);
			$uph->status = $status;
		}else $uph->status = self::VALID;
		$uph->insert();
		return $uph;
	}
	
	public static function existsWaiting($userId,$email,$code,$status=self::WAITING){
		return self::QValue()->field('id')
				->where(array('user_id'=>$userId,'email'=>$email,'code'=>$code,'status'=>$status,'last'=>true))
				->fetch();
	}
	
	public static function validEmail($userId,$email,$code,$waitingStatus=self::WAITING,$validStatus=self::VALID){
		if($uheId=self::existsWaiting($userId,$email,$code,$waitingStatus)){
			self::updateOneFieldByPk($uheId,'status',self::VALID);
			return $uheId;
		}
		return false;
	}
	
	/** Cancel a change of email */
	public static function cancelable($userId,$email,$code){
		return self::QOne()->fields('id,status')
			->where(array('user_id'=>$userId,'email'=>$email,'code'=>$code,'status'=>array(self::WAITING,self::VALID),'last'=>true,'ADDDATE(created,INTERVAL 15 DAY) >= CURDATE()'))->fetch();
	}
	
	public static function lastValid($userId,$except=false){
		$where=array('user_id'=>$userId,'status'=>self::VALID);
		if($except!==false) $where['id !=']=$except;
		return self::QValue()->fields('email')->where($where)->orderBy(array('created'=>'DESC'))->fetch();
	}
	
	public function details(){
		return $this->email;
	}
	
}