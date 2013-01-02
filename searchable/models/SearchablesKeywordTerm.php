<?php
/** @TableAlias('skt') @Created @Updated */
class SearchablesKeywordTerm extends SearchablesTypedTerm{ /* EXTENDS : methods type(), name(), ... */
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		 * @ForeignKey('SearchablesKeyword','id')
		*/ $keyword_id,
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		 * @ForeignKey('SearchablesTerm','id')
		*/ $term_id,
		/** @SqlType('tinyint(2) unsigned') @NotNull
		*/ $type,
		/** @SqlType('int(10) unsigned') @Null
		* @ForeignKey('SearchablesTerm','id')
		*/ $inherited_from;
	
	/* VALUE(searchable.SearchablesKeywordTerm.phpcontent) */
	
	public static function create($keywordId,$termName,$type){
		$termId=SearchablesTerm::createOrGet($termName,$type);
		return self::add($keywordId,$termId,$type);
	}
	public static function add($keywordId,$termId,$type){
		return self::QInsert()->ignore()->set(array('keyword_id'=>$keywordId,'term_id'=>$termId,'type'=>$type));
	}
	
	/* for ajaxCRDInputAutocomplete */
	
	public function id(){
		return $this->term_id;
	}
	
	public function nameHtml(){
		return ($this->inherited_from===null ? '' : '<i>').h($this->term).($this->inherited_from===null ? '' : '</i>').' '.$this->typeHtml();
	}
}