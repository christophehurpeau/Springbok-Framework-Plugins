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
		/* /IF *//* IF(searchable_seo) */,
		/** @SqlType('varchar(100)') @NotNull
		*/ $meta_title,
		/** @SqlType('varchar(200)') @NotNull @Default('""')
		* @Text
		*/ $meta_descr,
		/** @SqlType('varchar(255)') @NotNull @Default('""')
		*/ $meta_keywords,
		/** @SqlType('text') @Null
		*/ $descr
		/* /IF */;
	
	public static $hasManyThrough=array(
		'SearchablesTerm'=>array('joins'=>'SearchablesKeywordTerm')
	);
	
	
	public function auto_slug(){ return HString::slug($this->name); }
	public function auto_meta_title(){ return $this->name; }
	public function auto_meta_descr(){ return str_replace('&nbsp;',' ',html_entity_decode(strip_tags($this->descr),ENT_QUOTES,'UTF-8')); }
	public function auto_meta_keywords(){ return implode(', ',SearchablesTerm::QValues()->field('term')->withForce('SearchablesKeywordTerm')->addCondition('skt.keyword_id',$this->id)->orderBy('term')); }
	
	public static function autoEveryKeywords(){
		foreach(self::QAll() as $keyword){
			foreach(array('slug','meta_title','meta_descr','meta_keywords') as $metaName)
				if(empty($keyword->$metaName)) $keyword->$metaName=$keyword->{'auto_'.$metaName}();
			$keyword->update('slug','meta_title','meta_descr','meta_keywords');
		}
	}
	
	public function beforeSave(){
		if(!empty($this->name) && empty($this->slug)) $this->slug=HString::slug($this->name);
		return true;
	}
	
	public static function cleanPhrase($phrase){
		return trim(preg_replace('/[\s\,\+\-]+/',' ',$phrase));
	}
	
	
	public static function listKeywordIds($phraseCleaned){
		return SearchablesKeywordTerm::QValues()->field('DISTINCT keyword_id')
			->withForce('SearchablesTerm')
			->where(array(self::dbEscape(' '.$phraseCleaned.' ').' LIKE CONCAT("% ",st.term," %")'));
	}
}