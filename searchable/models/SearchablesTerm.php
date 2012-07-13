<?php
/** @TableAlias('st') @Created @Updated @DisplayField('term') /* IF(searchable.keywordTerms.seo) *\/ @Seo /* /IF *\/ */
class SearchablesTerm extends SSqlModel{
	const NONE=0,MAIN=1,MASCULINE_NOUN=2,FEMININ_NOUN=3,PLURAL_NOUN=4;
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @Unique @SqlType('varchar(100)') @NotNull
		*/ $term,
		/** @SqlType('tinyint(1) unsigned') @NotNull
		*  @Enum('None','Main','Masculine noun','Feminin noun','Plural noun'/* VALUE(searchables.terms.types) *\/)
		*/ $type;
	/*
	public static function addKeywords($keywordId,$terms){
		$data=array();
		foreach($terms as $term)
			$data[]=array('keyword_id'=>$keywordId,'term'=>self::cleanTerm($term));
	}
	*/
	
	/* IF(searchable.keywordTerms.text) */
	public /** @SqlType('text') @Null */ $text;
	public function afterUpdate(){ if(!empty($this->text)) VSeo::generate('SearchablesTerm',$this->id); }
	/* /IF */
	
	public static $hasManyThrough=array(
		'SearchablesKeyword'=>array('joins'=>'SearchablesKeywordTerm'),
	);
	
	/* VALUE(searchable.SearchablesTerm.phpcontent) */
	
	
	public static function createOrGet($term,$type){
		$term=self::cleanTerm($term);
		$id=self::QValue()->field('id')->where(array('term LIKE'=>$term));
		if($id!==false) return $id;
		$st=new SearchablesTerm;
		$st->term=$term;
		$st->type=$type;
		return $st->insert();
	}
	
	
	public static function cleanTerm($term){
		return trim(preg_replace('/[\s\,\+\\\\°]+/',' ',$term));
	}
	
	public function name(){ return $this->term; }
	
	
	public function auto_slug(){ return HString::slug($this->term); }
	/* IF(searchable.keywordTerms.seo) */
	public function auto_meta_title(){ return $this->term; }
	public function auto_meta_descr(){ return empty($this->text)?null:trim(preg_replace('/[\s\r\n]+/',' ',str_replace('&nbsp;',' ',html_entity_decode(strip_tags($this->text),ENT_QUOTES,'UTF-8')))); }
	public function auto_meta_keywords(){ return implode(', ',SearchablesTerm::QValues()->field('DISTINCT term')
			->withForce('SearchablesKeywordTerm',false)
			->leftjoin('SearchablesKeywordTerm',false,array('skt.keyword_id=skt2.keyword_id'),array('alias'=>'skt2'))
			->addCondition('skt2.term_id',$this->id)
			->orderBy('term')); }
	
	public function metaTitle(){ return empty($this->meta_title) ? $this->auto_meta_title() : $this->meta_title; }
	public function metaDescr(){ return empty($this->meta_descr) ? $this->auto_meta_descr() : $this->meta_descr; }
	public function metaKeywords(){ return empty($this->meta_keywords) ? $this->auto_meta_keywords() : $this->meta_keywords ; }
	/* /IF */
	
	
	public function beforeSave(){
		/* IF(searchable.keywordTerms.slug) */
		if(!empty($this->name) && empty($this->slug)) $this->slug=$this->auto_slug();
		/* /IF */
		return true;
	}
	
	public function afterSave(&$data=null){
		if(!empty($data['term'])){
			SearchableTermWord::add($this->id,$this->term);
		}
	}
}