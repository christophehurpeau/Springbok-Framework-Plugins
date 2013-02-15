<?php
/** @TableAlias('sta') */
class SearchablesTermAbbreviation extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('SearchablesTerm','id')
		*/ $term_id,
		/** @Pk @Unique @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('SearchablesTerm','id')
		*/ $abbr_id;
	
	
	public static function create($termId,$abbrId){
		if($res=self::QInsert()->ignore()->set(array('term_id'=>$termId,'abbr_id'=>$abbrId)))
			self::_update($abbrId);
		return $res;
	}
	
	public static function _update($termId){
		$term=SearchablesTerm::findValueTermById($termId);
		Searchable::QAll()->fields('id,name')->addCond('name LIKE','%'.$term.'%')->callback('_renormalize()');
	}
	
}