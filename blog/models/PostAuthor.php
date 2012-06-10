<?php
/* IF(blog_personalizeAuthors_enabled) */
/** @TableAlias('pa') */
class PostAuthor extends SSqlModel{
	public
		/** @Pk @SqlType('INT(10) unsigned') @NotNull
		* @ForeignKey('Post','id','onDelete'=>'CASCADE')
		*/ $post_id,
		/** @Pk @SqlType('INT(10) unsigned') @NotNull
		* @ForeignKey('PostsAuthor','id','onDelete'=>'CASCADE')
		*/ $author_id,
		/** @SqlType('datetime') @NotNull
		*/ $created;
	
	public static function create($postId,$authorId){
		return self::QInsert()->set(array('post_id'=>$postId,'author_id'=>$authorId));
	}
}
/* /IF */