<?php
/** @TableAlias('st') @Created @Updated */
class SearchableTerm extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		 * @ForeignKey('SearchableKeyword','id')
		*/ $keyword_id,
		/** @Unique @SqlType('varchar(100)') @NotNull
		*/ $term;
	
	public static function addKeywords($keywordId,$terms){
		$data=array();
		foreach($terms as $term)
			$data[]=array('keyword_id'=>$keywordId,'term'=>self::cleanTerm($term));
	}
	
	public static function cleanTerm($term){
		return SearchableKeyword::cleanKeyword($term);
	}
}