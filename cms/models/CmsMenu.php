<?php
/** @TableAlias('cm') @Created Updated */
class CmsMenu extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		 * @ForeignKey('Page','id','onDeleted'=>'CASCADE')
		*/ $page_id,
		/* IF(cms.multisite) */
		/** @Index @SqlType('tinyint(3) unsigned') @NotNull
		*/ $site,
		/* /IF */
		/** @SqlType('tinyint(2) unsigned') @NotNull @Default(0) @Comment('Rang d\'apparition')
		*  @Index
		*/ $position;
	
	public static function add($pageId/* IF(cms.multisite) */,$site/* /IF */){
		return self::QInsert()->ignore()->cols('page_id,/* IF(cms.multisite) */,site/* /IF */position')
				->values(array($pageId/* IF(cms.multisite) */,$site/* /IF */,self::findNextPosition()));
	}

	public static function findNextPosition(/* IF(cms.multisite) */$site/* /IF */){
		$currPos=self::QValue()->field('position')->orderBy(array('position'=>'DESC'))
				/* IF(cms.multisite) */->bySite($site)/* /IF */;
		return $currPos===false?0:((int)$currPos)+1;
	}


	public static function QListName(){
		return self::QList()->field('page_id')->with('Page','name')->orderBy('position')
				/* IF(cms.multisite) */->bySite($_GET['site.num'])/* /IF */;
	}
	
}