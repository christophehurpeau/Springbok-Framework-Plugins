<?php
/** @TableAlias('ucom')
* @Index('about_type','about_id','user_id')
*/
class UserComment extends SSqlModel{
	CONST VALID=1,WAITING_VALIDATION=2,DENIED=0;
	
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('User','id')
		*/ $user_id,
		/** @SqlType('int(1) unsigned') @NotNull
		* @Index @Enum(AConsts::ratingableTypes())
		*/ $about_type,
		/** @SqlType('int(10) unsigned') @NotNull
		*/ $about_id,
		/** @SqlType('varchar(100)') @NotNull
		*/ $title,
		/** @SqlType('text') @NotNull
		*/ $comment,
		/** @SqlType('text') @Null
		*/ $response,
		/** @SqlType('tinyint(1)') @NotNull
		* @Enum(1=>'Validé',2=>'En attente de validation',0=>'Refusé') @Default(2)
		*/ $status,
		/** @SqlType('datetime') @NotNull
		*/ $created,
		/** @SqlType('datetime') @Null @Default(NULL)
		*/ $updated;
	
	public static $hasOne=array(
		'UserRating'=>array(
			'foreignKey'=>'user_id','associationForeignKey'=>'user_id',
			'onConditions'=>array('ucom.about_type=urat.about_type','ucom.about_id=urat.about_id'),
			'fields'=>array('value'=>'rating'),'fieldsInModel'=>true
		),
	);
	
	public static function _paginationQueryCommentsOptions($userId){
		$with=array(
			'UserRating',
			'User'=>array('fields'=>'pseudo','fieldsInModel'=>true)
		);
		$where=array('status'=>UserComment::VALID,'u.status'=>array(User::VALID,User::ADMIN));
		if($userId!==false) $where=array('OR'=>array($where,'user_id'=>$userId));
		return array('with'=>$with,'where'=>$where);
	}
}