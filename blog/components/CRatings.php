<?php
class ACRatings{
	public static function addRating($aboutType,$aboutId,$rating,$userComment,$extraRating=null){
		if($rating===null && $userComment===null) self::renderText('0');
		
		$userId=CSecure::connected(); $succeed=false;
		if(!empty($userComment->pseudo) && User::findValuePseudoById($userId)===null && User::checkPseudo($userComment->pseudo)===true)
			User::updateOneFieldByPk($userId,'pseudo',$userComment->pseudo);
		if($rating!==null && !UserRating::existFor($aboutType,$userId,$aboutId)){
			$userRating=new UserRating;
			
			$userRating->value=$rating;
			$userRating->user_id=$userId;
			$userRating->about_type=$aboutType;
			$userRating->about_id=$aboutId;
			$succeed=$userRating->insert();
			
			if($extraRating!==null){
				$extraRating->rating_id=$succeed;
				$extraRating->insert();
			}
		}
		if($userComment!==null && !empty($userComment->comment)){
			if($succeed){
				$userComment->rating=$rating;
			}elseif(($rating=UserRating::idAndRatingValue($aboutType,$userId,$aboutId))){
				$userComment->rating=$rating->value;
			}else $userComment->rating=null;
			$userComment->user_id=$userId;
			$userComment->status=UserComment::WAITING_VALIDATION;
			$userComment->about_type=$aboutType;
			$userComment->about_id=$aboutId;
			$succeed=$userComment->insert();
		}
		if(!$succeed) self::renderText('0');
		elseif(empty($userComment->id)) self::renderText('1');
		else{
			$userComment->user_id=$userId;
			$userComment->pseudo=CSecure::user()->pseudo;
			$userComment->created=date('Y-m-d H:i:s');
			self::set_('comment',$userComment);
			self::set_('userId',$userId);
			self::render('_comment','user');
		}
	}
}
