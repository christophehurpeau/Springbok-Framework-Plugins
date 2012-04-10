<?php
/** @TableAlias('t') */
class PostsTag extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('VARCHAR(60)') @NotNull
		* @Unique
		* @MinLength(3)
		*/ $name,
		/** @SqlType('varchar(60)') @NotNull
		* @Unique
		*/ $slug,
		/** @Boolean @Default(true)
		*/ $slug_auto,
		/** @SqlType('datetime') @NotNull
		*/ $created,
		/** @SqlType('datetime') @Null @Default(NULL)
		*/ $updated;
	
	public static function create($name){
		$t=new PostsTag;
		$t->name=$name;
		if($t->insertIgnore())
			return $t->id;
		return PostsTag::findValueIdByName($name);
	}
	
	public function beforeInsert(){
		$this->slug=HString::slug($this->name);
		$this->slug_auto=true;
		return parent::beforeInsert();
	}
	
	public function beforeUpdate(){
		if(!empty($this->name) && $this->isSlugAuto()) $this->slug=HString::slug($this->name);
		return parent::beforeUpdate();
	}
	
	const MAX_SIZE=20;
	public static function findAllSize(){
		$models=self::QAll()
			->with('PostTag',array('isCount'=>true))
			->orderBy(array('postTags'=>'DESC'))
			->limit(self::MAX_SIZE);

		$total=0;
		foreach($models as $model)
			$total+=$model->postTags;

		$tags=array();
		if($total>0){
			foreach($models as $model)
				$tags[$model->name]=8+(int)(16*$model->postTags/($total+10));
			uksort($tags,'strcasecmp');
		}
		return $tags;
	}
}
