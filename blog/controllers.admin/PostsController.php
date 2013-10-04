<?php
Controller::$defaultLayout='admin/cms';
/** @Check('ACSecureAdmin') @Acl('Posts') */
class PostsController extends Controller{
	/** */
	static function index(){
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
		/*#if blog_personalizeAuthors_enabled*/PostAuthor::create($post->id,1);/*#/if*/
		redirect('/posts/edit/'.$post->id);
	}
	
	/** @ValidParams @Required('id') */
	static function edit(int $id){
		$post=Post::ById($id)->withParent('name,slug,meta_title,meta_descr,meta_keywords')->with('PostTag','tag_id')->with('PostCategory','category_id')
			->with('PostImage')
			/*#if blog_personalizeAuthors_enabled*/->with('PostAuthor','author_id')/*#/if*/
			->mustFetch();
		mset($post,$id);
		render();
	}
	
	/** @ValidParams @Required('id') */
	static function delete(int $id){
		Post::updateOneFieldByPk($id,'status',Post::DELETED);
		Post::onModified($id,true);
		redirect('/posts');
	}
	
	/** @ValidParams @AllRequired
	* post > @Valid('name','excerpt','content') */
	static function save(int $id,Post $post){
		$post->id=$id;
		//if(empty($post->meta_keywords)) $post->findWith('PostTag',array('fields'=>'tag_id'));
		if(isset($_POST['imageInText'])) PostImage::updateOneFieldByPk($id,'in_text',$_POST['imageInText']?true:false);
		$post->checkMetasSet();
		if(empty($post->slug)) $post->slug=$post->auto_slug();
		if($publish=isset($_POST['publish'])){
			$post->status=Post::PUBLISHED;
		}elseif(!isset($post->status)) $post->status=Post::DRAFT;
		$res=$post->save();
		PostHistory::create($post,PostHistory::SAVE);
		if($publish) redirect('/posts/edit/'.$id);
		else renderText($res);
	}


	/** @Ajax @ValidParams @AllRequired */
	static function selectImage(int $postId,int $imageId){
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
	static function autocomplete($term){
		self::renderJSON(SModel::json_encode(
			Post::QAll()->field('id')->withParent('name,slug')
				->where(array('sb.name LIKE'=>'%'.$term.'%'))
				->limit(14)
				->fetch(),
			'_autocomplete'
		));
	}

	/** @Ajax @ValidParams @Required('val') */
	static function checkId(int $val){
		$post=Post::QOne()->field('id')->withParent('name,slug')->byId($val)->fetch();
		self::renderJSON($post===false?'{"error":"Article inconnu"}':$post->toJSON_autocomplete());
	}
	
	/** @ValidParams */
	static function tools(){
		render();
	}
	
	/** */
	static function regenerateLatest(){
		VPostsLatest::destroy();
		redirect('/posts/tools');
	}
	/** */
	static function regenerateSitemap(){
		ACSitemapPosts::generate();
		redirect('/posts/tools');
	}
}