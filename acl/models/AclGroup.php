<?php
/** @TableAlias('aclg') @Created */
class AclGroup extends SSqlModel{
	use BTree;
	
	const GUEST=1,BASIC_USER=2;
	
	public
		/** @Pk @AutoIncrement @SqlType('tinyint(3) unsigned') @NotNull
		*/ $id,
		/** @SqlType('varchar(50)') @Null
		*/ $name;
	
	
	public static function afterCreateTable(){
		self::QInsert()->cols('id,name')->mvalues(array(
			array(1,_tC('Guest')),
			array(2,_tC('Basic user')),
		));
	}
	
	public static function findListName(){
		return self::QList()->fields('id,name')->where(array('id != 1 AND id != 2'));
	}
}