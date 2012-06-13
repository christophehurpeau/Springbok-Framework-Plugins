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
	
	public function afterSave(){
		VPostsTags::generate();
	}
	
	public function link(){
		return '/posts/tag/'.$this->slug;
	}
	
	const MAX_SIZE=20;
	public static function findAllSize(){
		$models=self::QListAll()->fields('name,slug')
			->with('PostTag',array('isCount'=>true))
			->orderBy(array('tags'=>'DESC'))
			->limit(self::MAX_SIZE);

		$total=0;
		foreach($models as $model)
			$total+=$model->tags;

		if($total>0){
			foreach($models as &$model)
				$model->size=8+(int)(16*$model->tags/($total+10));
				//$model->size=(int)(150*(1+(1.5*$model->tags-$total/2)/$total));
				
				//pointsize = cnt / maxcount * (maxfontsize - minfontsize) + minfontsize http://www.fastechws.com/tricks/sql/labels_and_tag_clouds.php
				//$model->size=8+round($model->tags / $total * /* EVAL 16-8 *//* HIDE */0/* /HIDE */ ,0);
			uksort($models,'strcasecmp');
		}
		return $models;
	}
}
