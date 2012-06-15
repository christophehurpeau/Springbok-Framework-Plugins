<?php
/** @TableAlias('ssk') @Created @Updated @Parent */
class SearchablesKeyword extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('varchar(100)') @NotNull @MinLenth(3)
		*/ $name/* IF(searchable_slug) */,
		/** @SqlType('varchar(100)') @NotNull @MinLenth(3)
		*/ $slug
		/* /IF */;
	
	public static $hasManyThrough=array(
		'SearchablesTerm'=>array('joins'=>'SearchablesKeywordTerm')
	);
	
	
	/* IF(searchable_slug_auto) */
	public function beforeSave(){
		if(!empty($this->name)) $this->slug=HString::slug($this->name);
		return true;
	}
	/* /IF */
	
	public static function cleanPhrase($phrase){
		return trim(preg_replace('/[\s\,\+\-]+/',' ',$phrase));
	}
	
	
	public static function listKeywordIds($phraseCleaned){
		return SearchablesKeywordTerm::QValues()->field('DISTINCT keyword_id')
			->withForce('SearchablesTerm')
			->where(array(self::dbEscape(' '.$phraseCleaned.' ').' LIKE CONCAT("% ",st.term," %")'));
	}
}