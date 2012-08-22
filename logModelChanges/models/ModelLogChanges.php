<?php
/** @TableAlias('mlc') @Created @CreatedBy */
class ModelLogChanges extends SSqlModel{
	const INSERT=0,UPDATE=1,DELETE=2;
	public
		/** @Pk @AutoIncrement @SqlType('bigint(20) unsigned') @NotNull
		*/ $id,
		/** @ SqlType('tinyint(1) unsigned') @Null
		 * @Enum('Insert','Update','Delete')
		*/ $type,
		/** @SqlType('tinytext') @Null
		*/ $primaryKeys,
		/** @SqlType('text') @NotNull
		*/ $data;
		
	public static function logInsert($data){
		self::QInsert()->cols('type,data')
			->values(array(self::INSERT,json_encode($data)));
	}
	public static function logUpdate($primaryKeys,$data){
		self::QInsert()->cols('type,primaryKeys,data')
			->values(array(self::UPDATE,json_encode($primaryKeys),json_encode($data)));
	}
}