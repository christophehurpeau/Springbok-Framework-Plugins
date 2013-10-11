<?php
class UsersController extends AController{
	/** @Ajax */
	static function isConnected(){
		if(!ACSecure::isConnected()) renderText('0');
		else self::_renderConnected();
	}
	
	/** @Ajax @Required('user')
	* user > @Valid('email')
	*/ function ajaxLogin(User $user){
		if(CValidation::hasErrors() || !ACSecure::authenticate($user,true,false))
			renderText('0');
		else self::_renderConnected();
	}
	
	
	protected static function _renderConnected(){
		$user=ACSecure::user();
		self::renderJSON(json_encode(array( 'userName'=>$user->name()/*#if users.pseudo*/, 'pseudo'=>$user->pseudo /*#/if*/)));
	}
	
	
	/** @Ajax @Required('user')
	* user > @Valid('email'/*#if users.pseudo*\/,'pseudo'/*#/if*\/)
	*/ function ajaxRegister(User $user){
		renderText(CValidation::hasErrors()?'0':User::register($user));
	}
	
	/** @Post @Required('user')
	 * user > @Valid('email','first_name','last_name') */
	static function register(User $user){
		if(ACSecure::isConnected()) redirect('/');
		if(CValidation::hasErrors()) render('login');
		else{
			$res=User::register($user,false);
			if($res==='1') CSession::setFlash('Enregistrement effectué. Vous devriez recevoir sous peu un email vous permettant de valider votre compte.','user/login');
			else CSession::setFlash('Impossible de vous enregistrer','user/login');
			redirect('/site/login');
		}
	}
	
	
	/*#if users.pseudo*/
	/** @Ajax @ValidParams @NotEmpty('val') */
	static function checkPseudo($val){
		$errorCode=User::checkPseudo(trim($val));
		renderText($errorCode===true ? '1' : $errorCode);
	}
	/*#/if*/
	
	/** @Ajax @ValidParams @NotEmpty('val') */
	static function checkEmail($val){
		$errorCode=User::checkEmail($val);
		renderText($errorCode===true ? '1' : $errorCode);
	}
	
	
	
	
	/** @Ajax */
	static function ajaxLostPassword($email){
		if(!empty($email)){
			if($user=User::findValidUserByEmail($email)){
				$user->email=$email;
				User::sendLostPassword($user);
				renderText('1');
			}
		}
		renderText('0');
	}
	
	
	/** @ValidParams @Id('userId') @NotEmpty('email','code') */
	static function changePassword(int $userId,string $email,string $code){
		$password = CHttpRequest::_POSTor('pwd');
		if( empty($password) ){
			if( !($uheId=UserHistoryEmail::existsWaiting($userId,$email,$code,UserHistoryEmail::WAITING_LOST_PASSWORD)) ){
				$message='Votre code ne correspond à aucune demande de changement de mot de passe.';
				$classMessage='error';
			}else{
				$message = null;
				// it's not likely that the user is connected, but if he is, disconnects him
				if(CSecure::isConnected()) ACSecure::logout();
				mset($userId,$email,$code);
			}
		}else{
			if( !($uheId=UserHistoryEmail::validEmail($userId,$email,$code,UserHistoryEmail::WAITING_LOST_PASSWORD,UserHistoryEmail::VALID_LOST_PASSWORD)) ){
				$message='Votre code ne correspond à aucune demande de changement de mot de passe.';
				$classMessage='error';
			}else{
				$pwd = USecure::hashWithSalt($password);
				$uphId = UserHistoryPassword::create($userId,UserHistoryPassword::LOST_PASSWORD,$pwd,$uheId);
				UserHistory::add(UserHistory::CHANGE_PWD_LOST,$uphId,$userId);
				User::updateOneFieldByPk($userId,'pwd',$pwd);
				$message='Votre nouveau mot de passe a bien été validé et enregistré. Vous pouvez maintenant vous connecter.';
				CSession::setFlash($message,'user/login',array('class'=>'message success'));
				redirect('/site/login');
			}
		}
		if($message !== null)
			{ mset($message,$classMessage); } // {} is required.
		render();
	}
	
	/** @ValidParams @Id('userId') @NotEmpty('email','code') */
	static function validEmail(int $userId,$email,$code){
		if( !($uheId=UserHistoryEmail::validEmail($userId,$email,$code)) ){
			$message='Votre code ne correspond à aucune validation de courriel.';
			$classMessage='error';
		}else{
			if(User::existByIdAndStatus($userId,User::WAITING)){
				User::updateOneFieldByPk($userId,'status',User::VALID);
				UserHistory::add(UserHistory::VALID_USER,$uheId,$userId);
				
				$message='Votre compte utilisateur a bien été validé'.(CSecure::isConnected()?'':', vous pouvez maintenant vous connecter').'.';
				$classMessage='success';
			}else{
				User::updateOneFieldByPk($userId,'email',$email);
				UserHistory::add(UserHistory::VALID_CHANGE_EMAIL,$uheId,$userId);
				
				CMail::send('user_changedEmail',array('email'=>$email),'Nouveau courriel validé - '.Config::$projectName.'.',$email);
				$message='Votre nouveau courriel a bien été validé et enregistré.';
				$classMessage='success';
			}
		}
		if(CSecure::connected()===$userId){
			CSession::setFlash($message,'user/me',array('class'=>'message '.$classMessage));
			redirect('/user/me');
		}
		mset($message,$classMessage);
		render();
	}
	
	/** @ValidParams @Id('userId') @NotEmpty('email','code') */
	static function cancelChangeEmail(int $userId,$email,$code,bool $confirm){
		if(($uhe=UserHistoryEmail::cancelable($userId,$email,$code))!==false){
			if($uhe->status===UserHistoryEmail::WAITING){
				$uhe->status=UserHistoryEmail::CANCELED;
				$uhe->update();
				UserHistory::add(UserHistory::CANCEL_CHANGE_EMAIL,$uhe->id,$userId);
				CMail::send('user_cancelChangeEmail',array('email'=>$email),'Demande de nouveau courriel rejetée - '.Config::$projectName.'.',$email);
				$message='La demande de nouveau courriel a bien été rejetée.';
				$classMessage='success';
			}elseif($uhe->status===UserHistoryEmail::VALID){
				$lastEmailValid=UserHistoryEmail::lastValid($userId,$uhe->id);
				if($lastEmailValid===false){
					$message='Impossible d\'annuler la demande de modification du courriel.';
					$classMessage='error';
				}else{
					if($confirm){
						$uhe->status=UserHistoryEmail::CANCELED_VALID;
						$uhe->update();
						User::updateOneFieldByPk($userId,'email',$lastEmailValid);
						UserHistory::add(UserHistory::CANCEL_CHANGE_EMAIL,$uhe->id,$userId);
						CMail::send('user_cancelChangedEmail',array('email'=>$email),'Demande de nouveau courriel rejetée - '.Config::$projectName.'.',$lastEmailValid);
						
						$message='La demande de nouveau courriel a bien été rejetée et l\'email restauré.';
						$classMessage='success';
					}else{
						$message='La demande de nouveau courriel a déjà été validée. Êtes-vous sûr de vouloir l\'annuler ?';
						$classMessage='mWarning';
					}
				}
			}else{
				$message='Impossible d\'annuler la demande de modification du courriel.';
				$classMessage='error';
			}
		}else{
			$message='Votre code ne correspond à aucune modification de courriel.';
			$classMessage='error';
		}
		mset($message,$classMessage);
		render();
	}
}