<?php
/** @TableAlias('u') @Created @Updated @DisplayField('IF(first_name is null,/* IF(users.pseudo) *\/IF(last_name IS NULL,pseudo,/* /IF *\/last_name/* IF(users.pseudo) *\/)/* /IF *\/,CONCAT(first_name," ",last_name))') */
class User extends SSqlModel{
	CONST ADMIN=9,WAITING=0,VALID=1,DISABLED=2,DELETED=3;
	
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @Unique @SqlType('VARCHAR(120)') @Null 
		* @Email @Required
		*/ $email,
		/** @SqlType('VARCHAR(100)') @Null
		* @Required
		*/ $pwd,
		/** @SqlType('VARCHAR(100)') @NotNull
		* @MinLength(2)
		*/ $first_name,
		/** @SqlType('VARCHAR(100)') @NotNull
		* @MinLength(2)
		*/ $last_name,
		/* IF(users.pseudo) */
		/** @SqlType('varchar(40)') @Null
		* @Required @Index
		*/ $pseudo,
		/* /IF */ 
		/** @SqlType('tinyint(1) unsigned') @NotNull @Default(0)
		* @Enum('En attente','Valide','Désactivé','Supprimé',9=>'Administrateur')
		*/ $status;
		
	public function name(){
		return $this->first_name===null ? (/* IF(users.pseudo) */$this->last_name === null ? $this->pseudo :/* /IF */ $this->last_name) : $this->first_name.' '.$this->last_name;
	}
	
	/* IF(users.pseudo) */
	
	public function publicName(){
		return $this->pseudo;
	}
	
	
	public static function checkPseudo($pseudo){
		if(empty($pseudo)) return '20';
		if(User::QExist()->where(array('pseudo LIKE'=>$pseudo))) return '21';
		if(ProhibitedWord::QExist()->where(array('LOCATE(word,'.ProhibitedWord::dbEscape($pseudo).') != 0'))) return '22';
		return true;
	}
	
	/* /IF */
	
	
	public static function checkEmail($email){
		if(empty($email)) return '20';
		if(User::QExist()->where(array('email LIKE'=>$email))) return '21';
		return true;
	}

	public function check($checkPseudoValidityAndEmailValidity=true){
		/* IF(users.pseudo) */if(isset($this->pseudo)) $this->pseudo=trim($this->pseudo);/* /IF */
		if(empty($this->type) || $this->type===User::LGS){
			foreach(array('first_name','last_name') as $field){
				if(empty($this->$field)) $this->$field=null;
				else{
					$this->$field=trim($this->$field);
					if(empty($this->$field)) $this->$field=null;
					else $this->$field=UString::checkAllLowerOrAllUpperCase($this->$field);
				}
			}
		}
		return $checkPseudoValidityAndEmailValidity===false || (/* IF(users.pseudo) */self::checkPseudo($this->pseudo)===true &&/* /IF */ self::checkEmail($this->email)===true);
	}
	
	
	
	
	public function validEmailLink($code){
		return '/users/validEmail/'.$this->id.'/'.urlencode($this->email).'/'.$code;
	}
	
	
	
	public static function register($user,$connectAfterRegistration=false){
		if(CSecure::isConnected()) return '0';
		if(!$user->check()) return '2';
		$password=UGenerator::randomLetters(12);
		$user->pwd=USecure::hashWithSalt($password);
		$user->status=User::WAITING;
		$user->insert('email','pseudo','pwd','status','first_name','last_name');
		
		if($connectAfterRegistration) CSecure::setConnected(CSecure::CONNECTION_AFTER_REGISTRATION,$user->id,$user->email);
		
		
		$uhe=UserHistoryEmail::create($user->id,$user->email);
		UserHistory::add(UserHistory::CREATE,$uhe->id,$user->id);
		$uphId=UserHistoryPassword::create($user->id,UserHistoryPassword::INITIAL,$user->pwd);
		CMail::send('user_registration',array('user'=>$user,'password'=>$password,'uhe'=>$uhe),'Bienvenue sur '.Config::$projectName,$user->email);
		return '1';
	}
	
	public static function sendLostPassword($user){
		CLogger::get('lostPassword')->log($user->id.': '.$user->email);
		$password=UGenerator::randomLetters(12);
		$pwd=USecure::hashWithSalt($password);
		$uphId=UserHistoryPassword::create($user->id,UserHistoryPassword::LOST_PASSWORD,$pwd);
		UserHistory::add(UserHistory::LOST_PWD,$uphId,$user->id);
		User::updateOneFieldByPk($user->id,'pwd',$pwd);
		CMail::send('user_lostPassword',array('user'=>$user,'password'=>$password),'Mot de passe perdu - '.Config::$projectName.'.',$user->email);
	}
}