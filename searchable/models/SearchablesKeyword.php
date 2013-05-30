<?php
/** @TableAlias('ssk') @Created @Updated @Parent
	/*#if searchable.keywords.slug*\/ @Slug('varchar(100)') @UniqueSlug /*#/if*\/
	/*#if searchable.keywords.translatable*\/ @Translatable /*#/if*\/
*/
class SearchablesKeyword extends SSqlModel{
	use BParent/*#if searchable.keywords.seo*/,BSeo/*#/if*/
		/*#if searchable.keywords.slug*/,BSlug/*#/if*/
		/*#if searchable.keywords.text*/,BTextContent/*#/if*/;
	
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('SearchablesTerm','id')
		*/ $id;
	
	/*#if searchable.keywords.text*/
	public static function findOneForSeo($id){
		return parent::QOne()/*#if !searchable.keywords.seo*/->with('MainTerm')/*#/if*/->where(array('id'=>$id));
	}
	/*#/if*/
	
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
	
	/*#value searchable.SearchablesKeyword.phpcontent*/
	
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
	
	public static function cleanPhrase($phrase){
		//return trim(preg_replace('/[\s\,\+\-]+/',' ',$phrase));
		return SearchablesTerm::cleanTerm($phrase);
	}
	
	
	public static function listKeywordIds($phraseCleaned){
		return SearchablesKeywordTerm::QValues()->field('DISTINCT keyword_id')
			->withForce('SearchablesTerm')
			->where(array(self::dbEscape(' '.UString::normalize($phraseCleaned).' ').' LIKE CONCAT("% ",st.normalized," %")'));
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