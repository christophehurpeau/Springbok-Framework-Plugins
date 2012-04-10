<?php
/** @TableAlias('alb') */
class PostsAlbum extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @Null
		* @ForeignKey('PostsAlbum','id','onDelete'=>'CASCADE')
		*/ $parent_id,
		/** @SqlType('varchar(128)') @NotNull
		*/ $name,
		/** @SqlType('datetime') @NotNull
		*/ $created,
		/** @SqlType('datetime') @Null @Default(NULL)
		*/ $updated;
	
	public static function create($parentId,$name){
		return self::QInsert()->set(array('parent_id'=>$parentId,'name'=>$name));
	}
}