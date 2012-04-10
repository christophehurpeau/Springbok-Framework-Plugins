<?php
/** @TableAlias('pi') */
class PostImage extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Post','id')
		*/ $post_id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('PostsImage','id')
		*/ $image_id,
		/** @Boolean @Default(true)
		*/ $in_text;
	
	public static function create($postId,$imageId){
		return self::QInsert()->values(array('post_id'=>$postId,'image_id'=>$imageId));
	}
}

