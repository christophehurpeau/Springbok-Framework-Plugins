<?php
/** @TableAlias('t') @Child('SearchablesKeyword') */
class PostsTag extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id;
	
	public static function create($name){
		$t=new PostsTag;
		$t->name=$name;
		if($t->insertIgnore())
			return $t->id;
		return PostsTag::findValueIdByName($name);
	}
	
	public function afterSave(){
		VPostsTags::generate();
	}
	
	public function link(){
		return '/posts/tag/'.$this->slug;
	}
	
	public static function withOptions(){
		return array('fields'=>'id','with'=>array('Parent'=>array('fields'=>'name,slug')));
	}
	
	const MAX_SIZE=20;
	public static function findAllSize(){
		$models=self::QListAll()->withParent('name,slug')
			->with('PostTag',array('isCount'=>true))
			->orderBy(array('tags'=>'DESC'))
			->limit(self::MAX_SIZE);

		$total=0;
		foreach($models as $model)
			$total+=$model->tags;

		if($total===0) return array();
		
		foreach($models as &$model)
			$model->size=8+(int)(16*$model->tags/($total+10));
			//$model->size=(int)(150*(1+(1.5*$model->tags-$total/2)/$total));
			
			//pointsize = cnt / maxcount * (maxfontsize - minfontsize) + minfontsize http://www.fastechws.com/tricks/sql/labels_and_tag_clouds.php
			//$model->size=8+round($model->tags / $total * /* EVAL 16-8 *//* HIDE */0/* /HIDE */ ,0);
		uksort($models,'strcasecmp');
		return $models;
	}
}
