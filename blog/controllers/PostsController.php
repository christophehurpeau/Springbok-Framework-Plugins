<?php
class PostsController extends AController{
	/** */
	function index(){
		set('posts',CPagination::create(Post::QListAll())->pageSize(10)->execute());
		render();
	}
	
	/** @ValidParams('/') @Required('slug') */
	function tag($slug,int $id){
		$postTag=PostsTag::QOne()->withParent('name,slug,descr')->addCondition('ssk.slug',$slug);
		notFoundIfFalse($postTag);
		if($id!==null && $postTag->id != $id) notFound();
		
		mset($postTag);
		set('posts',CPagination::create(Post::QListAll()
			->with('PostTag',array('forceJoin'=>true,'fields'=>false))
			->addCondition('pt.tag_id',$postTag->id))->pageSize(10)->execute());
		render();
	}
}