<?php
/** @TableAlias('st') @Created @Updated @DisplayField('term') @OrderByField('term') /* IF(searchable.keywordTerms.seo) *\/ @Seo @Index('slug') /* /IF *\/ */
class SearchablesTerm extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @Unique @SqlType('varchar(100)') @NotNull
		*/ $term/* IF(searchable.keywordTerms.slug) */,
		/** @Unique @SqlType('varchar(100)') @NotNull @MinLength(3)
		*/ $slug,
		/* /IF */
		/** @SqlType('tinyint(2) unsigned') @NotNull
		*/ $type;
	/*
	public static function addKeywords($keywordId,$terms){
		$data=array();
		foreach($terms as $term)
			$data[]=array('keyword_id'=>$keywordId,'term'=>self::cleanTerm($term));
	}
	*/
	
	/* IF(searchable.keywordTerms.text) */
	use BTextContent;
	/* /IF */
	
	public static $hasMany=array(
		'Types'=>array('modelName'=>'SearchablesTypedTerm','associationForeignKey'=>'term_id','fields'=>'type'),
	);
	public static $hasManyThrough=array(
		'SearchablesKeyword'=>array('joins'=>'SearchablesKeywordTerm'),
	);
	
	/* VALUE(searchable.SearchablesTerm.phpcontent) */
	
	
	public static function createOrGet($term,$type){
		$term=self::cleanTerm($term);
		if(empty($term)) throw new Exception('term is empty');
		$id=self::QValue()->field('id')->where(array('term LIKE'=>$term));
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
		return self::QValue()->field('id')->where(array('term LIKE'=>$term));
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
	
	
	public function beforeUpdate(){
		if(!empty($this->type)) SearchablesTypedTerm::addIgnore($this->id,$this->type);
		return true;
	}
	
	public function beforeSave(){
		if(!empty($this->term)) $this->term=trim($this->term);
		
		/* IF(searchable.keywordTerms.slug) */
		if(!empty($this->term) && empty($this->slug)){
			$this->slug=$this->auto_slug();
		}
		if(isset($this->id)){
			$oldSlug=self::QValue()->field('slug')->byId($this->id);
			if(!empty($oldSlug) && $oldSlug!=$this->slug) $this->oldSlug=$oldSlug;
		}
		/* /IF */
		
		/* IF(searchable.keywordTerms.seo) */ /* COPY */
		if(!empty($this->term) && empty($this->slug)){
			$this->slug=$this->auto_slug();
		}
		if(isset($this->id)){
			$oldSlug=self::QValue()->field('slug')->byId($this->id);
			if(!empty($oldSlug) && $oldSlug!=$this->slug) $this->oldSlug=$oldSlug;
		}
		/* /IF */
		
		
		/* IF(searchable.keywordTerms.text) */
		if(empty($this->text) && isset($this->text)) $this->text=null;
		/* /IF */
		return true;
	}
	
	public function afterSave(&$data=null){
		if(!empty($data['term'])){
			SearchableTermWord::add($this->id,$this->term);
		}
		/* IF(searchable.keywordTerms.slug) */
		if(!empty($this->oldSlug)){
			SearchablesTermSlugRedirect::add($this->oldSlug,$this->slug);
		}
		/* /IF */
		/* IF(searchable.keywordTerms.seo) */ /* COPY */
		if(!empty($this->oldSlug)){
			SearchablesTermSlugRedirect::add($this->oldSlug,$this->slug);
		}
		/* /IF */
	}
	
	public function adminLink(){
		return HHtml::link($this->term,'/searchableTerm/view/'.$this->id);
	}
	public function toJSON_adminAutocomplete(){
		return json_encode(array('id'=>$this->id,'value'=>$this->nameWithType(),'url'=>HHtml::url(AHGlossary::link($this),'index',true)));
	}
}