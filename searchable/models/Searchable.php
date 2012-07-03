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
	
	public function normalized(){ return trim(preg_replace('/[ \-\'\"]+/',' ',$this->name)); }
	
	public function beforeSave(){
		if(!empty($this->name)){
			$this->normalized=$this->normalized();
			if(empty($this->slug)) $this->slug=$this->auto_slug();
		}
		return true;
	}
	
	public function afterSave(&$data=null){
		if(!empty($data['name']) && $this->isVisible()){
			SearchableWord::add($this->id,$this->name);
		}
	}
	
	public function link(){
		return array('/:controller/:id-:slug',_tR(static::LINK_CONTROLLER),sprintf('%03d',$this->id),$this->slug);
	}
}