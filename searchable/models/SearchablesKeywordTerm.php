<?php
/** @TableAlias('skt') @Created @Updated */
class SearchablesKeywordTerm extends SearchablesTypedTerm{ /* EXTENDS : methods type(), name(), ... */
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		 * @ForeignKey('SearchablesKeyword','id')
		*/ $keyword_id,
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		 * @ForeignKey('SearchablesTerm','id')
		*/ $term_id,
		/** @SqlType('tinyint(2) unsigned') @NotNull
		*/ $type,
		/** @SqlType('tinyint(3) unsigned') @NotNull
		 * @Comment("0-9, 0=closest. Inherited have added proximities")
		*/ $proximity,
		/** @SqlType('int(10) unsigned') @Null
		* @ForeignKey('SearchablesKeyword','id')
		*/ $inherited_from;
	
	public static $belongsTo=array(
		'TermKeyword'=>array('modelName'=>'SearchablesKeyword','foreignKey'=>'term_id')
	);
	
	/* VALUE(searchable.SearchablesKeywordTerm.phpcontent) */
	
	public static function byPks($keywordId,$termId){
		return self::QOne()->where(array('keyword_id'=>$keywordId,'term_id'=>$termId))
			->with('SearchablesTerm',array('fields'=>'term','fieldsInModel'=>true))
			->with('TermKeyword',array('fields'=>'id'));
	}
	
	public static function create($keywordId,$termName,$type,$proximity){
		$termId=SearchablesTerm::createOrGet($termName,$type);
		return self::add($keywordId,$termId,$type,$proximity);
	}
	public static function add($keywordId,$termId,$type,$proximity){
		if(!self::QInsert()->ignore()->set(array('keyword_id'=>$keywordId,'term_id'=>$termId,'type'=>$type,'proximity'=>$proximity)))
			self::QUpdateOneField('proximity',$proximity)->limit1()->where(array('keyword_id'=>$keywordId,'term_id'=>$termId,'proximity >'=>$proximity));
	}
	
	/* for ajaxCRDInputAutocomplete */
	
	public function id(){
		return $this->term_id;
	}
	
	public function nameHtml(){
		$balise= $this->inherited_from===null ? false : ( $this->inherited_from===$this->term_id ? 'b' : 'i');
		return (empty($this->termKeyword->id) || ($this->inherited_from!==null && $this->inherited_from!==$this->term_id)?'':
										'<span class="keyword" rel="'.($this->inherited_from===$this->term_id?'true':'false').'"></span>')
			.'<span class="proximity">'.$this->proximity.'</span> '.h('>').' '
			. ($balise===false ? '' : '<'.$balise.'>').h($this->term).($balise===false ? '' : '</'.$balise.'>')
			.' '.$this->typeHtml();
	}
	
	public function isEditable(){
		return ($this->inherited_from!==null || $this->inherited_from!==$this->term_id) && $this->type!==self::ITSELF;
	}
	public function isDeletable(){
		return $this->isEditable();
	}
}