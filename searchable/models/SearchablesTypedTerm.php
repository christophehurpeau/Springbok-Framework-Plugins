<?php
/** @TableAlias('stt') @IndexUnique('term_id','type') @Created */
class SearchablesTypedTerm extends SSqlModel{ /* EXTENDED by SearchablesKeywordTerm */
	/* http://en.wikipedia.org/wiki/Category:Types_of_words */
	/* !!! searchables/web/js/_admin.js */
	const NONE=0,KEYWORD=1,MASCULINE_NOUN=20,FEMININ_NOUN=21,PLURAL_NOUN=22,EPICENE=23,
			ABBREVIATION=30,ACRONYM=31,
			SPELLING_MISTAKE=5;
	
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('SearchablesTerm','id')
		*/ $term_id,
		/** @SqlType('tinyint(2) unsigned') @NotNull
		*  @Enum('None','Keyword',20=>'Masculine noun',21=>'Feminin noun',22=>'Plural noun',23=>'Epicene',30=>'Abbreviation',31=>'Acronym',5=>'Spelling mistake'/* VALUE(searchables.terms.types) *\/)
		*/ $type;
	
	
	public static function createOrGet($termId,$type){
		$sttId=self::QValue()->field('id')->where(array('term_id'=>$termId,'type'=>$type));
		if($sttId!==false) return $sttId;
		return self::addIgnore($termId,$type);
	}
	public static function addIgnore($termId,$type){
		return self::QInsert()->ignore()->set(array('term_id'=>$termId,'type'=>$type));
	}
	
	public function jsonSerialize(){
		return array('id'=>$this->id(),'name'=>$this->name());
	}
	
	public function id(){
		return $this->term_id.'-'.$this->type;
	}
	public function name(){
		return $this->term.' ['.$this->type().']';
	}
	public function nameHtml(){
		return h($this->term).' '.$this->typeHtml();
	}
	public function typeHtml(){
		return '<span style="color:gray">['.($this->type===self::KEYWORD?'<b>':'').h($this->type()).($this->type===self::KEYWORD?'</b>':'').']</span>';
	}
	
	public function adminLink(){
		return HHtml::linkHtml($this->nameHtml(),'/searchableTerm/view/'.$this->term_id);
	}
}