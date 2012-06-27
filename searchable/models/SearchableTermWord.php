<?php
/** @TableAlias('stw') */
class SearchableTermWord extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('SearchablesTerm','id')
		*/ $term_id,
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('SearchablesWord','id')
		*/ $word_id;
	
	public static function add($termId,$term){
		$words=self::getWords($termId);
		foreach(SearchablesWord::explodePhrase($term) as $word){
			if($wordId=array_search($word,$words)){
				unset($words[$wordId]);
			}else{
				self::QInsert()->ignore()->set(array('term_id'=>$termId,'word_id'=>SearchablesWord::createOrGet($word)));
			}
		}
		if(!empty($words)) self::QDeleteAll()->where(array('term_id'=>$termId,'word_id'=>array_keys($words)));
	}
	
	private static function getWords($termId){
		return self::QList()->fields('word_id')->with('SearchablesWord','word')->byTerm_id($termId);
	}
}