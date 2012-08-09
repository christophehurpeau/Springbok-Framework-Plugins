<?php
/** @TableAlias('skt') @Created @Updated */
class SearchablesKeywordTerm extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		 * @ForeignKey('SearchablesKeyword','id')
		*/ $keyword_id,
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		 * @ForeignKey('SearchablesTerm','id')
		*/ $term_id;
		
	/* VALUE(searchable.SearchablesKeywordTerm.phpcontent) */
	
	public static function create($keywordId,$term_name){
		$termId=SearchablesTerm::createOrGet($term_name);
		return self::add($keywordId,$termId);
	}
	public static function add($keywordId,$termId){
		return self::QInsert()->ignore()->set(array('keyword_id'=>$keywordId,'term_id'=>$termId));
	}
}