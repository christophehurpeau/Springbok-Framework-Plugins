<?php
/** @TableAlias('pt') */
class PostTag extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Post','id','onDelete'=>'CASCADE')
		*/ $post_id,
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('PostsTag','id','onDelete'=>'CASCADE')
		*/ $tag_id,
		/** @SqlType('datetime') @NotNull
		*/ $created;
	
	public static $hasOne=array(
		'SearchablesKeywordTerm'=>array('foreignKey'=>'tag_id','associationForeignKey'=>'keyword_id'),
	);
	
	public static function create($postId,$tagId){
		$res=self::QInsert()->set(array('post_id'=>$postId,'tag_id'=>$tagId));
		if($res) PostPost::refind($postId);
		return $res;
	}
}
