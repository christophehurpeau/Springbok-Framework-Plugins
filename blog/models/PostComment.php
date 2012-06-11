<?php
/** @TableAlias('pcom')
* @Index('post_id','user_id')
*/
class PostComment extends SSqlModel{
	CONST VALID=1,WAITING_VALIDATION=2,DENIED=0;
	
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('User','id')
		*/ $user_id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Post','id')
		*/ $post_id,
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
		'PostRating'=>array(
			'foreignKey'=>'user_id','associationForeignKey'=>'user_id',
			'onConditions'=>array('pcom.post_id=prat.post_id'),
			'fields'=>array('value'=>'rating'),'fieldsInModel'=>true
		),
	);
	
	public static function _paginationQueryCommentsOptions($userId){
		$with=array(
			'PostRating',
			'User'=>array('fields'=>'pseudo','fieldsInModel'=>true)
		);
		$where=array('status'=>PostComment::VALID,'u.status'=>array(User::VALID,User::ADMIN));
		if($userId!==false) $where=array('OR'=>array($where,'user_id'=>$userId));
		return array('with'=>$with,'where'=>$where);
	}
}