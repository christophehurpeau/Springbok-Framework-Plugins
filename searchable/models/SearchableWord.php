<?php
/** @TableAlias('sw') */
class SearchableWord extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @Unique @SqlType('varchar(100)') @NotNull
		*/ $word,
		/** @Index @SqlType('int(10) unsigned') @NotNull
		*/ $count,
		/** @Index @SqlType('smallint(5) unsigned') @NotNull
		*/ $length;
	
	public static function &createOrIncrement($word){
		self::beginTransaction();
		/*if(!self::QUpdateOneField('count',array('`count`++'))){
			
		}*/
		
		$id=self::QValue()->field('id')->byWord($word);
		if($id===false){
			$id=self::QInsert()->set(array('word'=>$word,'length'=>strlen($word),'count'=>1));
		}else{
			self::updateOneFieldByPk($id,'count',array('`count`+1'));
		}
		self::commit();
		return $id;
	}
	
	public static function decrement($wordId){
		self::QUpdateOneField('count',array('`count`-1'))->where(array('id'=>$wordId,'count >'=>0));
		/*
			self::QDeleteOne()->where(array('word'=>&$word));
		}*/
	}
}