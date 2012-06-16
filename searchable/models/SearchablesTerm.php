<?php
/** @TableAlias('st') @Created @Updated @DisplayField('term') */
class SearchablesTerm extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @Unique @SqlType('varchar(100)') @NotNull
		*/ $term;
	/*
	public static function addKeywords($keywordId,$terms){
		$data=array();
		foreach($terms as $term)
			$data[]=array('keyword_id'=>$keywordId,'term'=>self::cleanTerm($term));
	}
	*/
	public static function cleanTerm($term){
		return HString::removeSpecialChars(trim(preg_replace('/[\s\,\+\\\\°]+/',' ',$term)));
	}
	
	public function name(){ return $this->term; }
	
	
	public function afterSave(&$data=null){
		if(!empty($data['term'])){
			SearchableTermWord::add($this->id,$this->term);
		}
	}
}