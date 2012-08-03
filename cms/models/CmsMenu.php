<?php
/** @TableAlias('cm') @Created Updated */
class CmsMenu extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		 * @ForeignKey('Page','id','onDeleted'=>'CASCADE')
		*/ $page_id,
		/** @SqlType('tinyint(2) unsigned') @NotNull @Default(0) @Comment('Rang d\'apparition')
		*  @Index
		*/ $position;
	
	public static function add($pageId){
		return self::QInsert()->ignore()->cols('page_id,position')->values(array($pageId,self::findNextPosition()));
	}

	public static function findNextPosition(){
		$currPos=self::QValue()->field('position')->orderBy(array('position'=>'DESC'));
		return $currPos===false?0:((int)$currPos)+1;
	}


	public static function QListName(){
		return self::QList()->field('page_id')->with('Page','name')->orderBy('position');
	}
	
}