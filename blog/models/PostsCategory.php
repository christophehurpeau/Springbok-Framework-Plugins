<?php
/** @TableAlias('c') @Child('SearchablesKeyword') @DisplayField('skmt.term') */
class PostsCategory extends SSqlModel{
	use BChild;
	
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @Boolean @Default(true)
		*/ $home_page;
	
	public static $belongsTo=array(
		'MainTerm'=>array('modelName'=>'SearchablesTerm','dataName'=>'term',0=>array('p_id'=>'id'),'fieldsInModel'=>true,'fields'=>array('term'=>'name','slug'),'alias'=>'skmt')
	);
	
	public static function create($name){
		$t=new PostsCategory;
		$t->term=$name;
		if($t->insertIgnore())
			return $t->id;
		return PostsCategory::findValueIdByName($name);
	}
	
	public static function QListName(){
		return /**/self::QList()->field('id')->with('MainTerm',array('fields'=>'term'))->orderBy(array('skmt.term'));
	}
	
}