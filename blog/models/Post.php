<?php
/** @TableAlias('p') @DisplayField('name') @Child('Searchable') */
class Post extends Searchable{
	const LINK_CONTROLLER='post',
		DRAFT=1,PUBLISHED=2,ARCHIVED=3,DELETED=4;
	
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		*  @ForeignKey('User','id')
		*/ $author_id,
		/** @SqlType('text') @NotNull
		*/ $excerpt,
		/** @SqlType('text') @NotNull
		*/ $content,
		/** @SqlType('tinyint(1)') @NotNull
		* @Enum(1=>'Draft',2=>'Published',3=>'Archived')
		*/ $status,
		/* IF(blog_comments_enabled) */
		/** @Boolean @Default(true)
		*/ $comments_allowed,
		/* /IF */
		/** @SqlType('datetime') @Null @Default(NULL)
		* @NotBindable
		*/ $published;
	
	public static $hasOne=array(
		'Rating'=>array('modelName'=>'PostRating','fields'=>array('ROUND(AVG(prat.value))'=>'rating'),'fieldsInModel'=>true),
	);
	
	public static $hasMany=array(
		'PostPost'=>array('onConditions'=>array('deleted'=>false)),
		'LinkedPost'=>array('modelName'=>'PostPost','associationForeignKey'=>'linked_post_id'),
	);
	public static $hasManyThrough=array('Post'=>array('joins'=>array('PostPost'=>array('associationForeignKey'=>'linked_post_id'))));
	
	
	public static function findLatest(){
		return Post::QAll()->byStatus(Post::PUBLISHED)->fields('id')->withParent('name')->orderByCreated()->limit(5);
	}

	public static function QListAll(){
		return/* */ Post::QAll()->withParent('name,slug,created,updated')->fields('id,excerpt,/* IF(blog_comments_enabled) */comments_allowed,/* /IF */published')
			->with('PostImage','image_id')
			->with('PostsTag',array('fields'=>'id','with'=>array('Parent'=>array('fields'=>'name/* IF(searchable_slug) */,slug/* /IF */'))))
			/* IF(blog_comments_enabled) */->with('PostComment',array('isCount'=>true,'onConditions'=>array('pcom.status'=>PostComment::VALID)))/* /IF */
			->byStatus(Post::PUBLISHED)
			->orderBy(array('sb.created'=>'DESC'));
	}
	
	public function beforeInsert(){ return true; }
	
	public function beforeSave(){ return true; }
	
	public function afterSave(&$data=null){
		VPost::destroy($this->id);
	}
	
	public function auto_meta_descr(){ return trim(preg_replace('/(\s*\n\s*\n\s*)+/',' ',str_replace('&nbsp;',' ',html_entity_decode(strip_tags($this->excerpt),ENT_QUOTES,'UTF-8')))); }
	public function auto_meta_keywords(){ return empty($this->tags)?'':implode(', ',PostsTag::QValues()->field('name')->byId($this->tags)->orderBy('name')); }
	
	
	public function isPublished(){return $this->status!==self::DRAFT;}
	
	public function toJSON_autocomplete(){
		return json_encode(array('id'=>$this->id,'value'=>$this->name,'url'=>HHtml::url($this->link(),Config::$site_url)));
	}
	public function toJSON_autocomplete_linkedposts(){
		return json_encode(array('id'=>$this->id,'value'=>$this->name,'pblsd'=>$this->isPublished()));
	}
	
	
	public function save(){
		if($this->status===self::PUBLISHED && !Post::existByIdAndStatus($this->id,self::PUBLISHED)) $this->published=array('NOW()');
		$res=$this->update();
		if($res && $this->status===self::PUBLISHED)
			self::onModified($this->id);
		return $res;
	}
	
	public static function onModified($postId){
		VPostsLatest::generate(); VPostsLatestMenu::generate(); VPost::generate($postId);
		ACSitemapPosts::generate();
	}
	
	public static function CRUDOptions(){
		return array('title'=>'Articles',
			'fields'=>'id,status,published',
			'with'=>array('Parent'=>array('fields'=>'name,created,updated')),
			'orderBy'=>array('created'=>'DESC')
		);
	}
	
	public static function withOptions(){
		return array('fields'=>'id','with'=>array('Parent'=>array('fields'=>'name,slug')));
	}
}