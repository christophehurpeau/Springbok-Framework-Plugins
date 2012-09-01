<?php
Controller::$defaultLayout='admin/cms';
/** @Check('ACSecureAdmin') @Acl('Posts') */
class PostsController extends Controller{
	/** */
	function index(){
		Post::Table()->fields('id,status')->withParent('name,created,updated')
			->where(array('status !='=>Post::DELETED))->orderBy(array('sb.created'=>'DESC'))
			->allowFilters()
			->paginate()->fields(array('id','name','status','created','updated'))->actionClick('edit')
			->render('Articles',true);
	}
	
	/** @ValidParams @Required('post')
	* post > @Valid('name')
	*/ function add(Post $post){
		$post->status=Post::DRAFT;
		$post->author_id=CSecure::connected();
		$post->visible=false;
		$post->insert();
		/* IF(blog_personalizeAuthors_enabled) */PostAuthor::create($post->id,1);/* /IF */
		redirect('/posts/edit/'.$post->id);
	}
	
	/** @ValidParams @Required('id') */
	function edit(int $id){
		$post=Post::ById($id)->withParent('name,slug,meta_title,meta_descr,meta_keywords')->with('PostTag','tag_id')->with('PostCategory','category_id')
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
		Post::onModified($id,true);
		redirect('/posts');
	}
	
	/** @ValidParams @AllRequired
	* post > @Valid('name','excerpt','content') */
	function save(int $id,Post $post){
		$post->id=$id;
		//if(empty($post->meta_keywords)) $post->findWith('PostTag',array('fields'=>'tag_id'));
		if(isset($_POST['imageInText'])) PostImage::updateOneFieldByPk($id,'in_text',$_POST['imageInText']?true:false);
		//foreach(array('slug','meta_title','meta_descr','meta_keywords') as $metaName) if(empty($post->$metaName)) $post->$metaName=$post->{'auto_'.$metaName}();
		if(empty($post->slug)) $post->slug=$post->auto_slug();
		$res=$post->save();
		PostHistory::create($post,PostHistory::SAVE);
		renderText($res);
	}


	/** @Ajax @ValidParams @AllRequired */
	function selectImage(int $postId,int $imageId){
		$pi=new PostImage;
		$pi->post_id=$postId;
		$pi->image_id=$imageId;
		$pi->replace();
		$pi->in_text=true;
		Post::onModified($postId);
		set_('image',$pi);
		set_('id',$postId);
		render('post_image');
	}
	
	
	/** @Ajax @ValidParams @Required('term') */
	function autocomplete($term){
		self::renderJSON(SModel::json_encode(
			Post::QAll()->field('id')->withParent('name,slug')
				->where(array('sb.name LIKE'=>'%'.$term.'%'))
				->limit(14)
			,'_autocomplete'
		));
	}

	/** @Ajax @ValidParams @Required('val') */
	function checkId(int $val){
		$post=Post::QOne()->field('id')->withParent('name,slug')->byId($val);
		self::renderJSON($post===false?'{"error":"Article inconnu"}':$post->toJSON_autocomplete());
	}
	
	/** @ValidParams */
	function tools(){
		render();
	}
	
	/** */
	function regenerateLatest(){
		VPostsLatest::generate();
		redirect('/posts/tools');
	}
	/** */
	function regenerateSitemap(){
		ACSitemapPosts::generate();
		redirect('/posts/tools');
	}
	
	/** @ValidParams @Required('id') */
	function test(int $id){
		$post=Post::QOne()->fields('id,excerpt,text')->withParent('name,slug')->byId($id);
	}
}