<?php
Controller::$defaultLayout='admin/blog';
/** @Check('ASecureAdmin') @Acl('Posts') */
class PostsController extends Controller{
	/** */
	function index(){
		$table=Post::Table()->fields('id,title,slug,status,created,updated')->where(array('status !='=>Post::DELETED))->orderByCreated()
			->allowFilters()
			->paginate()->fields(array('id','title','status','created','updated'))->actionClick('edit')
			->render('Articles',true);
	}
	
	/** @ValidParams @Required('post')
	* post > @Valid('title')
	*/ function add(Post $post){
		$post->status=Post::DRAFT;
		$post->author_id=CSecure::connected();
		$post->insert();
		/* IF(blog_personalizeAuthors_enabled) */PostAuthor::create($post->id,1);/* /IF */
		redirect('/posts/edit/'.$post->id);
	}
	
	/** @ValidParams @Required('id') */
	function edit(int $id){
		$post=Post::ById($id)->with('PostTag','tag_id')->with('PostCategory','category_id')
			->with('PostImage')
			/* IF(blog_personalizeAuthors_enabled) */->with('PostAuthor','author_id')/* /IF */
			;
		notFoundIfFalse($post);
		mset($post,$id);
		render();
	}
	
	/** @ValidParams @Required('id') */
	function delete(int $id){
		Post::updateOneFieldByPk($id,'status',Post::DELETED);
		redirect('/posts');
	}
	
	/** @ValidParams @AllRequired
	* post > @Valid('title','excerpt','content') */
	function save(int $id,Post $post){
		$post->id=$id;
		if(empty($post->meta_keywords)) $post->findWith('PostTag',array('fields'=>'tag_id'));
		//if(isset($_POST['imageInText'])) PostImage::QUpdateOneField('in_text',$_POST['imageInText']?true:false)->byPost_id($id)->limit1()->execute();
		foreach(array('slug','meta_title','meta_descr','meta_keywords') as $metaName) if(empty($post->$metaName)) $post->$metaName=$post->{'auto_'.$metaName}();
		$res=$post->save();
		PostHistory::create($post,PostHistory::SAVE);
		renderText($res);
	}

	
	/** @Ajax @ValidParams @Required('term') */
	function autocomplete($term){
		self::renderJSON(SModel::json_encode(
			Post::QAll()->fields('id,title,slug')
				->where(array('title LIKE'=>'%'.$term.'%'))
			,'_autocomplete'
		));
	}

	/** @Ajax @ValidParams @Required('val') */
	function checkId(int $val){
		$post=Post::QOne()->fields('id,title,slug')->byId($val);
		self::renderJSON($post===false?'{"error":"Article inconnu"}':$post->toJSON_autocomplete());
	}
	
	/** @ValidParams */
	function tools(){
		render();
	}
	
	/** @ValidParams */
	function regenerateLatest(){
		VPostsLatest::generate();
		redirect('/posts/tools');
	}
	
	
	/** @ValidParams @Required('id') */
	function test(int $id){
		$post=Post::QOne()->fields('id,title,slug,excerpt,text')->byId($id);
		renderText(UHtml::transformInternalLinks($post->excerpt,array(
			'article'=>function($id){$postSlug=Post::findValueSlugById($id); return array('/:controller/:id-:slug','posts',sprintf('%03d',$id),$postSlug);}
		)));
	}
}