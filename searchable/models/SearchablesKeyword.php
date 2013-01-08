<?php
/** @TableAlias('ssk') @Created @Updated @Parent /* IF(searchable.keywords.seo) *\/ @Seo /* /IF *\/ */
class SearchablesKeyword extends SSqlModel{
	use BParent,BSeo/* IF(searchable.keywords.text) */,BTextContent/* /IF */;
	
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('SearchablesTerm','id')
		*/ $id/* IF(searchable.keywords.slug) */,
		/** @Unique @SqlType('varchar(100)') @NotNull @MinLength(3)
		*/ $slug
		/* /IF */;
	
	/* IF(searchable.keywords.text) */
	public static function findOneForSeo($id){
		return parent::QOne()/* IF!(searchable.keywords.seo) */->with('MainTerm')/* /IF */->where(array('id'=>$id));
	}
	/* /IF */
	
	public static $belongsTo=array(
		'MainTerm'=>array('modelName'=>'SearchablesTerm','dataName'=>'term','foreignKey'=>'id','fieldsInModel'=>true,'fields'=>'term,slug','alias'=>'skmt')
	);
	
	public static $hasMany=array(
		'TermWithType'=>array('modelName'=>'SearchablesKeywordTerm','dataName'=>'terms','associationForeignKey'=>'keyword_id',
									'with'=>array('SearchablesTerm'=>['fields'=>'term','fieldsInModel'=>true],
													'TermKeyword'=>array('fields'=>'id')),
									'orderBy'=>array('skt.proximity','IF(keyword_id=term_id,0,1)')),
		'Types'=>array('modelName'=>'SearchablesTypedTerm','foreignKey'=>'id','associationForeignKey'=>'term_id','fields'=>'type'),
	);
	
	public static $hasManyThrough=array(
		'SearchablesTerm'=>array('joins'=>'SearchablesKeywordTerm'),
		//'TermWithType'=>array('modelName'=>'SearchablesTerm','dataName'=>'terms','joins'=>'SearchablesKeywordTerm','withOptions'=>array('SearchablesKeywordTerm'=>array('fields'=>'type'))),
		'SearchablesTypedTerm'=>array('joins'=>'SearchablesKeywordTerm'),
		'Keywords'=>array('modelName'=>'SearchablesKeyword','joins'=>array('KeywordsIds'=>array('associationForeignKey'=>'keyword_id')),'with'=>array('MainTerm'))
	);
	
	/* VALUE(searchable.SearchablesKeyword.phpcontent) */
	
	public function name(){
		return $this->term;
	}
	
	public function auto_slug(){ return HString::slug($this->term); }
	
	public function auto_meta_title(){ return $this->term; }
	public function auto_meta_descr(){ return trim(preg_replace('/[\s\r\n]+/',' ',str_replace('&nbsp;',' ',html_entity_decode(strip_tags($this->text),ENT_QUOTES,'UTF-8')))); }
	public function auto_meta_keywords(){ return implode(', ',SearchablesTerm::QValues()->field('term')->withForce('SearchablesKeywordTerm')->addCondition('skt.keyword_id',$this->id)->orderBy('term')); }
	
	
	public function beforeInsert(){
		return $this->id=SearchablesTerm::createOrGet($this->term,SearchablesTypedTerm::NONE);
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
		//return trim(preg_replace('/[\s\,\+\-]+/',' ',$phrase));
		return SearchablesTerm::cleanTerm($phrase);
	}
	
	
	public static function listKeywordIds($phraseCleaned){
		return SearchablesKeywordTerm::QValues()->field('DISTINCT keyword_id')
			->withForce('SearchablesTerm')
			->where(array(self::dbEscape(' '.$phraseCleaned.' ').' LIKE CONCAT("% ",st.term," %")'));
	}
	
	
	public static function addTerm($keywordId,$term,$proximity,$type){
		$termId=SearchablesTerm::createOrGet($term,$type);
		SearchablesKeywordTerm::add($keywordId,$termId,$type,$proximity);
		return $termId;
	}
	
	public function adminLink(){
		return HHtml::link($this->name(),'/searchableKeyword/view/'.$this->id);
	}
}