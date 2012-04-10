<?php
/** @TableAlias('urat')
* @Unique('about_type','about_id','user_id')
*/
class UserRating extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('User','id')
		*/ $user_id,
		/** @SqlType('int(1) unsigned') @NotNull
		* @Enum(AConsts::ratingableTypes())
		*/ $about_type,
		/** @SqlType('int(10) unsigned') @NotNull
		*/ $about_id,
		/** @SqlType('tinyint(1) unsigned') @NotNull
		*/ $value,
		/** @SqlType('datetime') @NotNull
		*/ $created;
	
	public static $hasOne=array(
	);
	
	public static function existFor($type,$userId,$aboutId){
		return self::QExist()->where(array('about_type'=>&$type,'about_id'=>&$aboutId,'user_id'=>&$userId));
	}
	public static function ratingValue($type,$userId,$aboutId){
		return self::QValue()->field('value')->where(array('about_type'=>&$type,'about_id'=>&$aboutId,'user_id'=>&$userId));		
	}
	public static function idAndRatingValue($type,$userId,$aboutId){
		return self::QOne()->fields('id,value')->where(array('about_type'=>&$type,'about_id'=>&$aboutId,'user_id'=>&$userId));		
	}
	
	public static function existForPost($userId,$postId){
		return self::existFor(AConsts::POST,$userId,$postId);
	}
	public static function ratingPostValue($userId,$postId){
		return self::ratingValue(AConsts::POST,$userId,$postId);
	}
	public static function idAndRatingPostValue($userId,$postId){
		return self::idAndRatingValue(AConsts::POST,$userId,$postId);
	}
}