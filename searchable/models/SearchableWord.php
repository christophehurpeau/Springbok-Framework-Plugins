<?php
/** @TableAlias('sw') */
class SearchableWord extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Searchable','id','onDelete'=>'CASCADE')
		*/ $searchable_id,
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('SearchablesWord','id')
		*/ $word_id;
	
	/* VALUE(searchable_word_phpcontent) */ 
	
	public static function add($searchableId,$name){
		$words=self::getWords($searchableId);
		foreach(SearchablesWord::explodePhrase($name) as $word){
			if($wordId=array_search($word,$words)){
				unset($words[$wordId]);
			}else{
				$wordId=SearchablesWord::createOrIncrement($word);
				self::QInsert()->ignore()->set(array('searchable_id'=>$searchableId,'word_id'=>$wordId));
			}
		}
		if(!empty($words)) self::decrementAndDeleteWords($searchableId,$words);
	}
	
	private static function getWords($searchableId){
		return self::QList()->fields('word_id')->with('SearchablesWord','word')->bySearchable_id($searchableId);
	}
	
	public static function deleteFor($searchableId){
		$words=self::getWords($searchableId);
		if(!empty($words)) self::decrementAndDeleteWords($searchableId,$words);
	}
	
	private static function decrementAndDeleteWords($searchableId,$words){
		self::beginTransaction();
		foreach($words as $wordId=>$word) SearchablesWord::decrement($wordId);
		self::QDeleteAll()->where(array('searchable_id'=>$searchableId,'word_id'=>array_keys($words)));
		self::commit();
	}
}