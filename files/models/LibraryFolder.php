<?php
/** @TableAlias('lfd') @Created @Updated */
class LibraryFolder extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @Null
		* @ForeignKey('LibraryFolder','id','onDelete'=>'CASCADE')
		*/ $parent_id,
		/** @SqlType('varchar(128)') @NotNull
		*/ $name;
	
	public static function create($parentId,$name){
		return self::QInsert()->set(array('parent_id'=>$parentId,'name'=>$name));
	}
}