<?php
/** @Check('ACSecureAdmin') @Acl('Posts') */
class PostPostsController extends Controller{
	/** @Ajax @ValidParams @Required('id') */
	static function view(int $id){
		$allPosts=Post::QAll()->fields('id,status')->withParent('name,slug')
			->with('LinkedPost',array('join'=>true,'fields'=>'deleted','fieldsInModel'=>true))
			->where(array('pp.post_id'=>$id));
		$posts=$deletedPosts=array();
		foreach($allPosts as &$post)
			$post->deleted===null ? $posts[]=&$post : $deletedPosts[]=&$post;
		mset($id,$posts,$deletedPosts);
		render();
	}
	
	/** @Ajax @ValidParams @Required('id') */
	static function refind(int $id){
		PostPost::refind($id);
	}
	
	
	/** @Ajax @ValidParams @AllRequired */
	static function delete(int $postId,int $linkedPostId){
		if(PostPost::QUpdateOneField('deleted',true)->byPost_idAndLinked_post_idAndManual($postId,$linkedPostId,false)->limit1())
			self::renderText('1');
		elseif(PostPost::QDeleteOne()->byPost_idAndLinked_post_idAndManual($postId,$linkedPostId,true))
			self::renderText('2');
		else self::renderText('0');
	}
	
	/** @Ajax @ValidParams @AllRequired */
	static function undelete(int $postId,int $linkedPostId){
		$res=PostPost::QUpdateOneField('deleted',false)->byPost_idAndLinked_post_id($postId,$linkedPostId)->limit1();
		if(!$res) renderText('0');
		else{
			PostPost::refind($postId);
			renderText(PostPost::existByPost_idAndLinked_post_id($postId,$linkedPostId)?'1':'2');
		}
	}


	/** @Ajax @ValidParams @AllRequired */
	static function add(int $postId,int $linkedPostId){
		renderText(PostPost::add($postId,$linkedPostId)?'1':'0');
	}

	/** @Ajax @ValidParams @Required('term') */
	static function autocomplete(int $postId,$term){
		self::renderJSON(SModel::json_encode(
			Post::QAll()->fields('DISTINCT id,status')->withParent('name,slug')
				->with('LinkedPost',array('fields'=>false,'join'=>true))
				->where(array('id !='=>$postId,'sb.name LIKE'=>'%'.$term.'%','OR'=>array('pp.post_id IS NULL','pp.post_id !='=>$postId)))
			,'_autocomplete_linkedposts'
		));
	}
}