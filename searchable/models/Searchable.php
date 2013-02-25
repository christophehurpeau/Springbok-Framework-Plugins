<?php
/** @TableAlias('sb') @Created @Updated @Parent /* IF(searchable_seo) *\/ @Seo /* /IF *\/ */
class Searchable extends SSqlModel{
	use BParent,BNormalized/* IF(searchable_seo) */,BSlug,BSeo/* /IF */;
	
	const INVALID=0,VALID=1,DELETED=2;
	
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('varchar(300)') @NotNull @MinLenth(3)
		*/ $name,
		/** @SqlType('varchar(500)') @NotNull @MinLenth(3)
		*/ $html_name,
		/* IF(searchable_order_field) */
		/** @SqlType('varchar(300)') @NotNull
		* @Index
		*/ $order,
		/** @Boolean @Default(true)
		*/ $visible;
	
	public static $beforeSave=array('_setIfName');
	public static $afterSave=array('_reindexIfName');
	
	
	public function htmlName(){
		return UString::callbackWords($this->name,function($word,$dot){
			$escapedWord=h($word.$dot);
			
			$term=SearchablesTerm::QOne()
				->withForce('SearchablesTermAbbreviation',array('associationForeignKey'=>'term_id',
						'with'=>array('SearchablesTerm'=>array('alias'=>'stabbr','fields'=>false,'foreignKey'=>'abbr_id'))))
				->where(!empty($dot) ? array('OR'=>array('stabbr.term LIKE'=>$word,'stabbr.term LIKE'=>$word.$dot))
									 : array('stabbr.term LIKE'=>$word));
			if($term!==false) return '<abbr title="'.h($term->term).'">'.$escapedWord.'</abbr>';
			return $escapedWord;
		});
	}

	public function _renormalize(){
		$this->updated=false;
		$this->normalized=$this->normalized();
		$this->html_name=$this->htmlName();
		unset($this->name);
		$this->update('normalized','html_name');
	}
	
	public function _setIfName(){
		if(!empty($this->name)){
			$this->html_name=$this->htmlName();
			/* IF(searchable_order_field) */
			if(empty($this->order)) $this->order=$this->name;
			/* /IF */
		}
		return true;
	}
	
	private function _reindexIfName($data=null){
		if(!empty($data['name']) || (!empty($this->name) && array_key_exists('visible',$data))){ /* isset will return false if $data['visible']===null */
			$this->reindex();
		}
	}
	
	public function reindex(){
		if($this->isVisible()) SearchableWord::add($this->id,$this->name);
		else SearchableWord::deleteFor($this->id);
	}
	
	public function link($action=null,$more=''){
		return array('/:controller/:id-:slug(/:action/*)?',_tR(static::LINK_CONTROLLER),sprintf('%03d',$this->id),$this->slug,$action===null?'':_tR($action),$more);
	}
}