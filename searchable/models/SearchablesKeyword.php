<?php
/** @TableAlias('ssk') @Created @Updated @Parent /* IF(searchable.keywords.seo) *\/ @Seo /* /IF *\/ */
class SearchablesKeyword extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('SearchablesTerm','id')
		*/ $id/* IF(searchable.keywords.slug) */,
		/** @Unique @SqlType('varchar(100)') @NotNull @MinLength(3)
		*/ $slug
		/* /IF */;
	
	/* IF(searchable.keywords.text) */
	public /** @SqlType('text') @Null */ $text;
	public function afterUpdate(){ if(!empty($this->text)) VSeo::generate('SearchablesKeyword',$this->id); }
	
	public static function findOneForSeo($id){
		return parent::QOne()/* IF!(searchable.keywords.seo) */->with('MainTerm')/* /IF */->where(array('id'=>$id));
	}
	/* /IF */
	
	public static $belongsTo=array(
		'MainTerm'=>array('modelName'=>'SearchablesTerm','dataName'=>'term','foreignKey'=>'id','fieldsInModel'=>true,'fields'=>'term,slug','alias'=>'skmt')
	);
	
	public static $hasManyThrough=array(
		'SearchablesTerm'=>array('joins'=>'SearchablesKeywordTerm')
	);
	
	/* VALUE(searchable.SearchablesKeyword.phpcontent) */
	
	public function name(){
		return $this->term;
	}
	
	public function auto_slug(){ return HString::slug($this->term); }
	
	public function auto_meta_title(){ return $this->term; }
	public function auto_meta_descr(){ return trim(preg_replace('/[\s\r\n]+/',' ',str_replace('&nbsp;',' ',html_entity_decode(strip_tags($this->text),ENT_QUOTES,'UTF-8')))); }
	public function auto_meta_keywords(){ return implode(', ',SearchablesTerm::QValues()->field('term')->withForce('SearchablesKeywordTerm')->addCondition('skt.keyword_id',$this->id)->orderBy('term')); }
	
	public function metaTitle(){ return empty($this->meta_title) ? $this->auto_meta_title() : $this->meta_title; }
	public function metaDescr(){ return empty($this->meta_descr) ? $this->auto_meta_descr() : $this->meta_descr; }
	public function metaKeywords(){ return empty($this->meta_keywords) ? $this->auto_meta_keywords() : $this->meta_keywords ; }
	/* IF(searchable.keywords.seo) */
	/* /IF */
	
	
	public function beforeInsert(){
		return $this->id=SearchablesTerm::createOrGet($this->term,SearchablesTerm::MAIN);
	}
	
	public function beforeSave(){
		/* IF(searchable.keywords.slug) */
		if(!empty($this->term) && empty($this->slug)) $this->slug=$this->auto_slug();
		/* /IF */
		/* IF(searchable.keywords.text) */
		if(empty($this->text) && isset($this->text)) $this->text=null;
		/* /IF */
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
	
	
	public static function addTerm($keywordId,$term,$type){
		$termId=SearchablesTerm::createOrGet($term,$type);
		SearchablesKeywordTerm::QInsert()->ignore()->set(array('term_id'=>$termId,'keyword_id'=>$keywordId));
		return $termId;
	}
}