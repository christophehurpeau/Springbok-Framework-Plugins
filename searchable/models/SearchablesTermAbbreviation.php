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
	
	public static function _update($abbrId){
		$terms=SearchablesTerm::findValuesTermById($abbrId);
		self::_updateTerms($terms);
	}
	
	private static function _updateTerms($terms){
		if(empty($terms)) return;
		Searchable::QAll()->fields('id,name')
			->addCond('normalized RLIKE','(^| )('.implode('|',array_map('preg_quote',array_map(array('UString','normalize'),$terms))).')( |$)')
			->callback('_renormalize()');
	}
	
	public static function _updateAllAbbr($termId){
		$terms=SearchablesTerm::QValues()->field('term')->withForce('SearchablesTermAbbreviation',array(array('id'=>'abbr_id')))
				->where(array('sta.term_id'=>$termId));
		self::_updateTerms($terms);
		
	}
}