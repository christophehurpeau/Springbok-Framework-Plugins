<?php
class PostsController extends AController{
	/** */
	function index(){
		set('posts',Post::QListAll()->paginate()->pageSize(10));
		render();
	}
	
	/** @ValidParams('/') @Required('slug') */
	function tag($slug,int $id){
		$postTag=PostsTag::QOne()->addCondition('skmt.slug',$slug);
		if($postTag===false){
			if($id!==null){
				$postTag=PostsTag::QOne()->addCondition('id',$id);
			}else{
				$postTag=PostsTag::QOne()
						->innerjoin('SearchablesTermSlugRedirect',false,array('stsr.new_slug=skmt.slug','stsr.direct'=>true))
						->addCondition('stsr.old_slug LIKE',$slug);
			}		
			notFoundIfFalse($postTag);
			redirectPermanent($postTag->link());
		}
		if($id!==null && $postTag->id != $id) notFound();
		
		mset($postTag);
		set('posts',Post::QListAll()
			->withForce('PostTag',false)
			->addCondition('pt.tag_id',$postTag->id)
			->paginate()->pageSize(10));
		render();
	}
}