<?php
/** @TableAlias('sb') @Created @Updated @Parent */
class Searchable extends SSqlModel{
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
		/* /IF */
		/** @SqlType('varchar(300)') @NotNull @MinLenth(3)
		*/ $slug,
		/** @Boolean @Default(true)
		*/ $visible/* IF(searchable_seo) */,
		/** @SqlType('varchar(128)') @Null
		*/ $meta_title,
		/** @SqlType('varchar(200)') @Null
		* @Text
		*/ $meta_descr,
		/** @SqlType('varchar(255)') @Null
		*/ $meta_keywords
		/* /IF */;
	
	public function normalized(){ return trim(preg_replace('/[ \-\'\"]+/',' ',$this->name)); }
	public function auto_slug(){ return HString::slug($this->name); }
	
	public function auto_meta_title(){ return $this->name; }
	public function metaTitle(){ return empty($this->meta_title) ? $this->auto_meta_title() : $this->meta_title; }
	
	public function beforeInsert(){
		if(empty($this->slug)) $this->slug=$this->auto_slug();
		return true;
	}
	public function beforeSave(){
		if(!empty($this->name)){
			$this->normalized=$this->normalized();
			if(empty($this->slug)) $this->slug=$this->auto_slug();
		}
		return true;
	}
	
	public function afterSave(&$data=null){
		if(!empty($data['name'])){
			SearchableWord::add($this->id,$this->name);
		}
	}
	
	public function name(){ return $this->name; }
	public function link(){
		return array('/:controller/:id-:slug',static::LINK_CONTROLLER,sprintf('%03d',$this->id),$this->slug);
	}
}