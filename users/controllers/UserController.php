<?php
/** @Check */
class UserController extends AController{
	/** */
	static function index(){
		render();
	}
	
	/** */
	static function me(User $user,bool $save){
		if($user !== null){
			if($user->check(false)){
				$moreInfos=''; $messageType='success';
				$user->id=CSecure::connected();
				$currentUser=CSecure::user();
				$user->type=$currentUser->type;
				/*#if users.pseudo*/
				if($user->pseudo===$currentUser->pseudo || User::checkPseudo($user->pseudo)!==true) unset($user->pseudo);
				/*#/if*/
				if(isset($user->email) && $user->email!==$currentUser->email){
					$user->email=strtolower($user->email);
					if(User::checkEmail($user->email)===true){
						$uhe=UserHistoryEmail::create($user->id,$user->email);
						UserHistory::add(UserHistory::CHANGE_EMAIL,$uhe->id);
						CMail::send('user_changeEmail',array('user'=>$user,'uhe'=>$uhe),'Validation du nouveau courriel - '.Config::$projectName.'.',$user->email);
						CMail::send('user_infoChangeEmail',array('user'=>$user,'uhe'=>$uhe),'Changement Courriel - '.Config::$projectName.'.',$currentUser->email);
						$moreInfos.=' Pour activer votre nouveau courriel, cliquez sur le lien situé dans l\'email que vous devriez recevoir dans quelques instants.';
					}else{
						$messageType='error';
						$moreInfos.=' Cependant, l\'adresse email est déjà utilisée et ne peut donc pas être modifiée';
					}
				}
				unset($user->email);
				
				self::_updateUser($currentUser,$user);
				
				if($currentUser->type!==User::SITE){
					/*#if users.pseudo*/if(!empty($user->pseudo)) $currentUser->pseudo=$user->pseudo;/*#/if*/
					$_POST['user']=$currentUser->_getData();
				}
				CSession::setFlash('Vos informations ont bien été enregistrées.'.$moreInfos,'user/me','message '.$messageType);
			}else CSession::setFlash('Vos informations ne sont pas valides.','user/me','message error');
		}
		set('user',CSecure::user());
		render();
	}

	private static function _updateUser($currentUser,$user){
		if($currentUser->type===User::SITE){
			if($currentUser->first_name!==$user->first_name || $currentUser->last_name!==$user->last_name || $currentUser->gender!==$user->gender/*#if users.pseudo*/ || isset($user->pseudo)/*#/if*/){
				UserHistory::add(UserHistory::UPDATE);
				$originalData=User::QRow()->byId($user->id)->fields('/*#if user.searchable*/p_id,/*#/if*/first_name,last_name,gender/*#if users.pseudo*/,pseudo/*#/if*/')->fetch();
				/*#if user.searchable*/ $pId=$originalData['p_id']; /*#/if*/
				$isUpdated=$user->updateCompare($originalData);
				/*#if user.searchable*/
				if($isUpdated===true){
					$user->p_id=$pId; $user->visible=true;
					$user->name=$user->first_name.' '.$user->last_name;
					$user->updateParent();
				}
				/*#/if*/
			}
		}/*#if users.pseudo*/elseif(!empty($user->pseudo)){
			if($user->pseudo!==$currentUser->pseudo){
				UserHistory::add(UserHistory::UPDATE);
				$user->update('pseudo');
			}
		}/*#/if*/
	}
	
	/** @ValidParams @AllRequired */
	static function changePassword($old_password,$new_password,$new_password_confirm){
		$old_password=trim($old_password); $new_password=trim($new_password); $new_password_confirm=trim($new_password_confirm);
		$error=null;
		$actualPassword=User::findValuePwdById($userId=CSecure::connected());
		if(USecure::hashWithSalt($old_password) !== $actualPassword) $error='Votre mot de passe actuel ne correspond pas à celui que vous avez entré.';
		elseif($new_password !== $new_password_confirm) $error='Votre nouveau mot de passe ne correspond pas au mot de passe de confirmation.';
		else{
			$pwd=USecure::hashWithSalt($new_password);
			$uhpId=UserHistoryPassword::create($userId,UserHistoryPassword::USER_DEFINED,$pwd);
			UserHistory::add(UserHistory::CHANGE_PWD,$uhpId);
			User::updateOneFieldByPk($userId,'pwd',$pwd);
			CSession::setFlash('Mot de passe modifié !','user/me',array('class'=>'message success'));
			redirect('/user/me');
		}
		set('errorPasswordChange',$error);
		set('user',CSecure::user());
		render('me');
	}
	
}