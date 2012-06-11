<?php
class PostsController extends AController{
	/** */
	function index(){
		set('posts',CPagination::create(Post::QAll()->fields('id,title,slug,excerpt,created,published,updated')
						->with('PostImage','image_id')->orderByCreated())->pageSize(10)->execute());
		render();
	}
	
	/** @ValidParams('/') @Required('slug') */
	function tag($slug,int $id){
		$postTag=PostsTag::findOneBySlug($slug);
		notFoundIfFalse($slug);
		if($id!==null && $postTag->id != $id) notFound();
		
		mset($postTag);
		set('posts',CPagination::create(Post::QAll()->fields('id,title,slug,excerpt,created,published,updated')
			->with('PostTag',array('forceJoin'=>true,'fields'=>false))
			->with('PostImage','image_id')->addCondition('pt.tag_id',$postTag->id)->orderByCreated())->pageSize(10)->execute());
		render();
	}
}
