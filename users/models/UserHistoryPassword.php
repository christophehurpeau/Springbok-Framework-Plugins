<?php
/** @TableAlias('uhp') */
class UserHistoryPassword extends SSqlModel{
	const INITIAL=1,LOST_PASSWORD=2,USER_DEFINED=3;
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('User','id')
		*/ $user_id,
		/** @SqlType('VARCHAR(100)') @NotNull
		*/ $pwd,
		/** @SqlType('tinyint(1) unsigned') @NotNull
		* @Enum(1=>'Initial','Mot de passe perdu','Défini par l\'utilisateur')
		*/ $type,
		/** @SqlType('int(10) unsigned') @Null
		* @ForeignKey('UserHistoryEmail','id')
		*/ $user_history_email,
		/** @SqlType('datetime') @NotNull
		*/ $created;
	
	public static function create($userId,$type,$pwd,$userHistoryEmail=null){
		$uph=new UserHistoryPassword;
		$uph->user_id = $userId;
		$uph->pwd = $pwd;
		$uph->type = $type;
		$uph->user_history_email = $userHistoryEmail;
		return $uph->insert();
	}
	
	public function details(){
		return $this->type();
	}
}