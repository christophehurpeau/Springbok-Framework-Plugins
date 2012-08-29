<?php
/** @TableAlias('t') @Child('SearchablesKeyword') @DisplayField('skmt.term')  */
class PostsTag extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id;
	
	public static $belongsTo=array(
		'MainTerm'=>array('modelName'=>'SearchablesTerm','dataName'=>'term','foreignKey'=>'p_id','fieldsInModel'=>true,
			'fields'=>array('term'=>'name'/* IF(searchable.keywordTerms.seo) */,'slug'/* /IF */ /* IF(searchable.keywordTerms.slug) */,'slug'/* /IF */),'alias'=>'skmt'),
		'Keyword'=>array('modelName'=>'SearchablesKeyword','dataName'=>'keyword','foreignKey'=>'p_id','fieldsInModel'=>true,
			'fields'=>array('slug'))
	);
	
	public static function create($name){
		if($id=self::QValue()->field('id')->with('MainTerm')->addCondition('skmt.term LIKE',$name))
			return $id;
		$t=new PostsTag;
		$t->term=$name;
		if($id=$t->insert())
			return $id;
		throw new Exception('Unable to create Tag : ',short_debug_var($t));
	}
	
	
	
	public function afterSave(){
		VPostsTags::generate();
	}
	
	public function link(){
		return '/posts/tag/'.$this->slug;
	}
	
	public static function withOptions(){
		return array('fields'=>'id','with'=>array('MainTerm'/* IF(searchable.keywords.slug) */,'Keyword'/* /IF */));//array('fields'=>'id','with'=>array('Parent'=>array('fields'=>'name/* IF(searchable_slug) */,slug/* /IF */'))
	}

	public static function QOne(){
		return parent::QOne()->with('MainTerm')/* IF(searchable.keywords.slug) */->with('Keyword')/* /IF */;
	}
	
	const MAX_SIZE=20;
	public static function findAllSize(){
		$models=self::QListAll()->setFields(false)->with('MainTerm')/* IF(searchable.keywords.slug) */->with('Keyword')/* /IF */
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
			//$model->size=8+round($model->tags / $total * /* EVAL 16-8 */0 ,0);
		uksort($models,'strcasecmp');
		return $models;
	}
	
	public static function QListName(){
		return /**/self::QList()->field('id')->with('MainTerm',array('fields'=>'term'))->orderBy(array('skmt.term'));
	}
	
	public function toJSON_adminAutocomplete(){
		return json_encode(array('id'=>$this->id,'value'=>$this->name(),'url'=>HHtml::url($this->link(),'index',true)));
	}
	
	public static function internalLink($id){
		$tag=new PostsTag; $tag->id=$id;
		$tag->slug=PostsTag::QValue()->noFields()->with('MainTerm',array('fields'=>'slug'))->addCondition('id',$id);
		return $tag->link();
	}
}
