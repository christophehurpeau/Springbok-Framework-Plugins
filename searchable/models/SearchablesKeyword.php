<?php
/** @TableAlias('ssk') @Created @Updated @Parent */
class SearchablesKeyword extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @Unique @SqlType('varchar(100)') @NotNull @MinLenth(3)
		*/ $name/* IF(searchable_slug) */,
		/** @Unique @SqlType('varchar(100)') @NotNull @MinLenth(3)
		*/ $slug
		/* /IF *//* IF(searchable_seo) */,
		/** @SqlType('varchar(100)') @Null
		*/ $meta_title,
		/** @SqlType('varchar(200)') @Null
		* @Text
		*/ $meta_descr,
		/** @SqlType('text') @Null @MaxLength(1000)
		*/ $meta_keywords,
		/** @SqlType('text') @Null
		*/ $descr
		/* /IF */;
	
	public static $hasManyThrough=array(
		'SearchablesTerm'=>array('joins'=>'SearchablesKeywordTerm')
	);
	
	
	public function auto_slug(){ return HString::slug($this->name); }
	/* IF(searchable_seo) */
	public function auto_meta_title(){ return $this->name; }
	public function auto_meta_descr(){ return trim(preg_replace('/[\s\r\n]+/',' ',str_replace('&nbsp;',' ',html_entity_decode(strip_tags($this->descr),ENT_QUOTES,'UTF-8')))); }
	public function auto_meta_keywords(){ return implode(', ',SearchablesTerm::QValues()->field('term')->withForce('SearchablesKeywordTerm')->addCondition('skt.keyword_id',$this->id)->orderBy('term')); }
	
	public function metaTitle(){ return empty($this->meta_title) ? $this->auto_meta_title() : $this->meta_title; }
	public function metaDescr(){ return empty($this->meta_descr) ? $this->auto_meta_descr() : $this->meta_descr; }
	public function metaKeywords(){ return empty($this->meta_keywords) ? $this->auto_meta_keywords() : $this->meta_keywords ; }
	/* /IF */
	
	
	public function beforeSave(){
		/* IF(searchable_slug) */
		if(!empty($this->name) && empty($this->slug)) $this->slug=$this->auto_slug();
		/* /IF */
		return true;
	}
	
	public function afterInsert(&$data=null){
		if(!empty($data['name'])) SearchablesKeywordTerm::create($this->id,$data['name']);
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