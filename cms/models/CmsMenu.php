<?php
/** @TableAlias('cm') @Created Updated */
class CmsMenu extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		 * @ForeignKey('Page','id','onDeleted'=>'CASCADE')
		*/ $page_id,
		/*#if cms.multisite*/
		/** @Index @SqlType('tinyint(3) unsigned') @NotNull
		*/ $site,
		/*#/if*/
		/** @SqlType('tinyint(2) unsigned') @NotNull @Default(0) @Comment('Rang d\'apparition')
		*  @Index
		*/ $position;
	
	public static function add($pageId/*#if cms.multisite*/,$site/*#/if*/){
		return self::QInsert()->ignore()->cols('page_id,/*#if cms.multisite*/,site/*#/if*/position')
				->values(array($pageId/*#if cms.multisite*/,$site/*#/if*/,self::findNextPosition()));
	}

	public static function findNextPosition(/*#if cms.multisite*/$site/*#/if*/){
		$currPos=self::QValue()->field('position')->orderBy(array('position'=>'DESC'))
				/*#if cms.multisite*/->bySite($site)/*#/if*/;
		return $currPos===false?0:((int)$currPos)+1;
	}


	public static function QListName(){
		return self::QList()->field('page_id')->with('Page','name')->orderBy('position')
				/*#if cms.multisite*/->bySite($_GET['site.num'])/*#/if*/;
	}
	
}