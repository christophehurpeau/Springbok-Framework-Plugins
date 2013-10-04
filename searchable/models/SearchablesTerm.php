<?php
/** @TableAlias('st') @Created @Updated @DisplayField('term') @OrderByField('term') @IndexSlug @UniqueNormalized */
class SearchablesTerm extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @Unique @SqlType('varchar(100)') @NotNull
		*/ $term,
		/** @SqlType('tinyint(2) unsigned') @NotNull
		*/ $type;
	/*
	public static function addKeywords($keywordId,$terms){
		$data=array();
		foreach($terms as $term)
			$data[]=array('keyword_id'=>$keywordId,'term'=>self::cleanTerm($term));
	}
	/* I F(searchable.keywordTerms.seo) *\/ @UniqueSlug /* /I F *\/ <= removed...
	*/
	
	use BNormalized
		/*#if searchable.keywordTerms.slug*/,BSlug/*#/if*/
		/*#if searchable.keywordTerms.seo*/,BSeo/*#/if*/
		/*#if searchable.keywordTerms.text*/,BTextContent/*#/if*/
		;
	
	
	public static $hasMany=array(
		'Types'=>array('modelName'=>'SearchablesTypedTerm','associationForeignKey'=>'term_id','fields'=>'type'),
	);
	public static $hasManyThrough=array(
		'SearchablesKeyword'=>array('joins'=>'SearchablesKeywordTerm'),
		'Abbreviations'=>array('modelName'=>'SearchablesTerm','alias'=>'stabbr',
				'joins'=>array('SearchablesTermAbbreviation'=>array(array('id'=>'abbr_id'))))
	);
	public static $beforeSave=array('_st_beforeSave');
	public static $afterSave=array('_st_afterSave');
	
	/*#value searchable.SearchablesTerm.phpcontent*/
	
	public function normalized(){ return UString::normalize($this->term); }
	
	public function _setNormalizedIfName(){
		if(!empty($this->term)){
			$this->normalized=$this->normalized();
		}
		return true;
	}
	public function _setSlugIfName(){
		if(!empty($this->term) && empty($this->slug)) $this->slug=$this->auto_slug();
		return true;
	}
	
	public function _renormalize(){
		$this->updated=false;
		$this->normalized=$this->normalized();
		/*#if searchable.keywordTerms.slug*/
		if(empty($this->slug)) $this->slug=$this->auto_slug();
		else unset($this->slug);
		/*#/if*/
		unset($this->term);
		$this->update('normalized'/*#if searchable.keywordTerms.slug*/,'slug'/*#/if*/);
	}
	
	
	public static function createOrGet($term,$type){
		$term=self::cleanTerm($term);
		if(empty($term)) throw new Exception('term is empty');
		$id=self::QValue()->field('id')->where(array('normalized'=>UString::normalize($term)))->fetch();
		if($id!==false){
			SearchablesTypedTerm::addIgnore($id,$type);
			return $id;
		}
		$st=new SearchablesTerm;
		$st->term=$term;
		$st->type=$type;
		$id=$st->insert();
		SearchablesTypedTerm::addIgnore($id,$type);
		return $id;
	}
	public static function get($term){
		$term=self::cleanTerm($term);
		return self::QValue()->field('id')->where(array('OR'=>array('term LIKE'=>$term,'normalized'=>UString::normalize($term))))->fetch();
	}
	
	public static function cleanTerm($term){
		return trim(preg_replace('/[\s\,\+\\\\°]+/',' ',$term));
	}
	
	public function name(){ return $this->term; }
	
	
	public function auto_slug(){ return HString::slug($this->term); }
	/*#if searchable.keywordTerms.seo*/
	public function auto_meta_title(){ return $this->term; }
	public function auto_meta_descr(){ return empty($this->text)?null:trim(preg_replace('/[\s\r\n]+/',' ',str_replace('&nbsp;',' ',html_entity_decode(strip_tags($this->text),ENT_QUOTES,'UTF-8')))); }
	public function auto_meta_keywords(){ return implode(', ',SearchablesTerm::QValues()->field('DISTINCT term')
			->withForce('SearchablesKeywordTerm',false)
			->leftjoin('SearchablesKeywordTerm',false,array('skt.keyword_id=skt2.keyword_id'),array('alias'=>'skt2'))
			->addCondition('skt2.term_id',$this->id)
			->orderBy('term'))
			->fetch(); }
	
	/*#/if*/
	
	
	public static $beforeUpdate=array('_addTypeInSearchablesTypedTerm');
	
	public function _addTypeInSearchablesTypedTerm(){
		if(!empty($this->type)) SearchablesTypedTerm::addIgnore($this->id,$this->type);
		return true;
	}
	
	public function _st_beforeSave(){
		if(!empty($this->term)) $this->term=trim($this->term);
		
		/*#if searchable.keywordTerms.slug*/
		if(isset($this->id) && !empty($this->slug) && empty($this->oldSlug)){
			$oldSlug=self::QValue()->field('slug')->byId($this->id)->fetch();
			if(!empty($oldSlug) && $oldSlug!=$this->slug) $this->oldSlug=$oldSlug;
		}
		/*#/if*/
		return true;
	}
	
	public function _st_afterSave(&$data=null){
		if(!empty($data['term'])){
			if(SearchableTermWord::add($this->id,$this->term))
				SearchablesTermAbbreviation::_updateAllAbbr($this->id);
		}
		/*#if searchable.keywordTerms.slug*/
		if(!empty($this->oldSlug)){
			SearchablesTermSlugRedirect::add($this->oldSlug,$this->slug);
			unset($this->oldSlug);
		}
		/*#/if*/
		return true;
	}
	
	public function adminLink(){
		return HHtml::link($this->term,'/searchableTerm/view/'.$this->id);
	}
	public function toJSON_adminAutocomplete(){
		return json_encode(array('id'=>$this->id,'value'=>$this->name(),'url'=>HHtml::url(AHGlossary::link($this),'index',true)));
	}
}