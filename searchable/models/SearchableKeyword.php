<?php
/** @TableAlias('sk') @Created @Updated @Parent */
class SearchableKeyword extends SSqlModelParent{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('varchar(100)') @NotNull @MinLenth(3)
		*/ $name,
		/* IF(searchable_slug) */
		/** @SqlType('varchar(100)') @NotNull
		*/ $slug
		/* /IF */;
	
	
	/* IF(searchable_slug_auto) */
	public function beforeSave(){
		if(!empty($this->name)) $this->slug=HString::slug($this->name);
		return true;
	}
	/* /IF */
	
	public static function cleanKeyword($keyword){
		return trim(preg_replace('/[\s\,\+\\]+',' ',$phrase));
	}
	
	public static function listKeywords($phrase){
		$phrase=trim(preg_replace('/[\s\,\+\-]+',' ',$phrase));
		$phrase=HString::removeSpecialChars($phrase);
		
		$keywords=self::QAll()
			->with('SearchableTerm',false)
			->where(self::dbEscape(' '.$phrase.' ').' LIKE CONCAT("% ",st.term," %")')
			->groupBy('sk.id');
		return $keywords;
	}
}