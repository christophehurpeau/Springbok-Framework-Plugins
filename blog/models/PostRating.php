<?php
/** @TableAlias('prat')
* @IndexUnique('post_id','user_id')
*/
class PostRating extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('User','id')
		*/ $user_id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Post','id')
		*/ $post_id,
		/** @SqlType('tinyint(1) unsigned') @NotNull
		*/ $value,
		/** @SqlType('datetime') @NotNull
		*/ $created;
	
	public static $hasOne=array(
	);
	
	public static function exist($userId,$postId){
		return self::QExist()->where(array('post_id'=>&$postId,'user_id'=>&$userId));
	}
	public static function ratingValue($userId,$postId){
		return self::QValue()->field('value')->where(array('post_id'=>&$postId,'user_id'=>&$userId));		
	}
	public static function idAndRatingValue($userId,$postId){
		return self::QOne()->fields('id,value')->where(array('post_id'=>&$postId,'user_id'=>&$userId));		
	}
	
}