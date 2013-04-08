<?php
/** @TableAlias('p') @DisplayField('name') @Child('Searchable','name,slug,created,updated') */
class Post extends Searchable{
	use BChild;
	
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
		* @Enum(1=>'Draft',2=>'Published',3=>'Archived',4=>'Deleted')
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
		'LinkedPost'=>array('modelName'=>'PostPost',0=>array('id'=>'linked_post_id')),
		'SearchableWord'=>array(array('p_id'=>'searchable_id')),
	);
	public static $hasManyThrough=array(
		'Post'=>array('joins'=>array('PostPost'=>array(0=>array('id'=>'linked_post_id')))),
		'SearchablesKeywordTerm'=>array('joins'=>'PostTag'),
	);
	
	
	public static function findLatest(){
		return Post::QAll()->byStatus(Post::PUBLISHED)->fields('id')->withParent('name')->orderByCreated()->limit(5);
	}

	public static function QListAll(){
		return/* */ Post::QAll()->withParent('name,slug,created,updated')->fields('id,/* IF(blog_comments_enabled) */comments_allowed,/* /IF */published')
			->with('PostImage','image_id')
			->with('PostsTag',PostsTag::withOptions())
			/* IF(blog_comments_enabled) */->with('PostComment',array('isCount'=>true,'onConditions'=>array('pcom.status'=>PostComment::VALID)))/* /IF */
			->byStatus(Post::PUBLISHED)
			->addCondition('sb.visible',true)
			->orderBy(array('sb.created'=>'DESC'));
	}
	
	public function beforeInsert(){ return true; }
	
	public function beforeSave(){ return true; }
	
	public function afterSave($data=null){
		VPost::destroy($this->id);
	}
	
	public function auto_meta_descr(){ return trim(preg_replace('/[\s\r\n]+/',' ',str_replace('&nbsp;',' ',html_entity_decode(strip_tags($this->excerpt),ENT_QUOTES,'UTF-8')))); }
	public function auto_meta_keywords(){
		/* DEV */ if(!isset($this->tags)) throw new Exception('Please find post tags'); /* /DEV */
		return empty($this->tags)?'':implode(', ',is_int($this->tags[0])?
				PostsTag::QValues()->setFields(false)->with('MainTerm','term')->byId($this->tags)->orderBy(array('skmt.term'))
				: array_map(function(&$t){return $t->name;},$this->tags));
	}
	
	
	public function isPublished(){return $this->status!==self::DRAFT;}
	
	public function toJSON_autocomplete(){
		return json_encode(array('id'=>$this->id,'value'=>$this->name,'url'=>HHtml::url($this->link(),'index',true)));
	}
	public function toJSON_autocomplete_linkedposts(){
		return json_encode(array('id'=>$this->id,'value'=>$this->name,'pblsd'=>$this->isPublished()));
	}
	
	public function save(){
		if($this->status===self::PUBLISHED && !Post::existByIdAndStatus($this->id,self::PUBLISHED)) $this->published=array('NOW()');
		$res=$this->update();
		$this->p_id=self::QValue()->field('p_id')->byId($this->id);
		$this->visible=$this->isPublished();
		$this->updateParent();
		if($res && $this->status===self::PUBLISHED)
			self::onModified($this->id);
		return $res;
	}
	
	public static function onModified($postId,$delete=false){
		VPostsLatest::destroy(); VPostsLatestMenu::destroy();
		$delete ? VPost::destroy($postId) : VPost::generate($postId);
		ACSitemapPosts::generate();
	}
	
	public static function CRUDOptions(){
		return array('title'=>'Articles',
			'fields'=>'id,status,published',
			'with'=>array('Parent'=>array('fields'=>'name,created,updated')),
			'orderBy'=>array('created'=>'DESC')
		);
	}
	
	public static function withOptions($options=array()){
		$options['fields']='id';
		$options['with']=array('Parent'=>array('fields'=>'name,slug'));
		$options['orderBy']=array('sb.created'=>'DESC');
		return $options;
	}
	
	public static function internalLink($id){
		$post=new Post; $post->id=$id;
		$post->slug=Searchable::QValue()->field('slug')->with('Post',array('fields'=>false))->addCondition('p.id',$id);
		return $post->link();
	}
	
	public function link($action=null,$more=''){
		return array('/:controller/:id-:slug(/:action/*)?',_tR(static::LINK_CONTROLLER),sprintf('%03d',$this->id),$this->slug,$action===null?'':_tR($action),$more);
		//return array('/:id-:slug/:action/*',$this->id,$this->slug,$action===null?'':_tR($action),$more);
	}
}