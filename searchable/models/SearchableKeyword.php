<?php
/** @TableAlias('sk') @Created */
class SearchableKeyword extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Searchable','id')
		*/ $searchable_id,
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('SearchablesKeyword','id')
		*/ $keyword_id;
	
	public static function create($searchableId,$keywordId){
		return self::QInsert()->ignore()->cols('searchable_id,keyword_id')
						->values(array($searchableId,$keywordId))
						->execute();
	}
	
	public static function del($searchableId,$keywordId){
		return self::QDeleteAll()->where(array('searchable_id'=>$searchableId,'keyword_id'=>$keywordId))->execute();
	}
}