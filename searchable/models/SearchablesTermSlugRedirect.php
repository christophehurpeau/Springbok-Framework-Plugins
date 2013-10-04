<?php
/** @TableAlias('stsr') */
class SearchablesTermSlugRedirect extends SSqlModel{
	public
		/** @Pk @SqlType('varchar(100)') @NotNull
		*/ $old_slug,
		/** @Pk @SqlType('varchar(100)') @NotNull
		*/ $new_slug,
		/** @Boolean @Default(true)
		*/ $direct,
		/** @SqlType('datetime') @NotNull
		*/ $created;
	
	public static function add($oldSlug,$newSlug){
		if(empty($oldSlug) || empty($newSlug)) return;
		$psr=new self;
		$psr->old_slug=$oldSlug;
		$psr->new_slug=$newSlug;
		$psr->direct=true;
		$psr->insertIgnore();
		if(self::QUpdateOneField('direct',false)->byNew_slug($oldSlug)->execute())
			self::QInsertSelect()->ignore()->query(self::QAll()->setFields(array('old_slug','('.UPhp::exportString($newSlug).')','("")','NOW()'))->byNew_slug($oldSlug))->execute();
	}
	
	
	public static function get($oldSlug){
		return self::QValue()->field('new_slug')->byOld_slugAndDirect($oldSlug,true)->fetch();
	}
	
	public static function findTerm($oldSlug){
		return SearchablesTerm::QOne()->field('slug')->innerjoin('SearchablesTermSlugRedirect',false,array('stsr.new_slug=st.slug','stsr.direct'=>true))
					->byText(true)->addCondition('stsr.old_slug LIKE',$oldSlug)
					->fetch();
	}
}