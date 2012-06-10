<?php
/** @TableAlias('pc') */
class PostCategory extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Post','id','onDelete'=>'CASCADE')
		*/ $post_id,
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('PostsCategory','id','onDelete'=>'CASCADE')
		*/ $category_id,
		/** @SqlType('datetime') @NotNull
		*/ $created;
	
	public static function create($postId,$catId){
		return self::QInsert()->set(array('post_id'=>$postId,'category_id'=>$catId));
	}
}
