<?php
/** @TableAlias('sb') @Created @Updated @Parent /* IF(searchable_seo) *\/ @Seo /* /IF *\/ */
class Searchable extends SSeoModel{
	const INVALID=0,VALID=1,DELETED=2;
	
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('varchar(300)') @NotNull @MinLenth(3)
		*/ $name,
		/** @SqlType('varchar(300)') @NotNull
		* @Index
		*/ $normalized,
		/* IF(searchable_order_field) */
		/** @SqlType('varchar(300)') @NotNull
		* @Index
		*/ $order,
		/* /IF *//* IF!(searchable_seo) */
		/** @SqlType('varchar(300)') @NotNull @MinLenth(3)
		*/ $slug,/* /IF */
		/** @Boolean @Default(true)
		*/ $visible;
	
	public function normalized(){ return UString::normalize($this->name); }
	
	public function beforeSave(){
		if(!empty($this->name)){
			$this->normalized=$this->normalized();
			if(empty($this->slug)) $this->slug=$this->auto_slug();
			/* IF(searchable_order_field) */
			if(empty($this->order)) $this->order=$this->name;
			/* /IF */
		}
		return true;
	}
	
	public function afterSave($data=null){
		if(!empty($data['name'])){
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