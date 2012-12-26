<?php
/** @TableAlias('skk') @Created @Updated */
class SearchablesKeywordKeyword extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		 * @ForeignKey('SearchablesKeyword','id')
		*/ $keyword_id,
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		 * @ForeignKey('SearchablesKeyword','id')
		*/ $rel_keyword_id;
	
	public static $belongsTo=array(
		'Keyword'=>array('modelName'=>'SearchablesKeyword','foreignKey'=>'keyword_id'),
		'RelKeyword'=>array('modelName'=>'SearchablesKeyword','foreignKey'=>'rel_keyword_id')
	);
	
	public static function add($keywordId,$relKeywordId){
		self::QInsert()->ignore()->set(array('keyword_id'=>$keywordId,'rel_keyword_id'=>$relKeywordId));
		self::QInsert()->ignore()->set(array('keyword_id'=>$relKeywordId,'rel_keyword_id'=>$keywordId));
	}
	public static function del($keywordId,$relKeywordId){
		self::QDeleteAll()->where(array(
			'OR'=>array(
				array('keyword_id'=>$keywordId,'rel_keyword_id'=>$relKeywordId),
				array('keyword_id'=>$relKeywordId,'rel_keyword_id'=>$keywordId)
			)
		));
	}
}