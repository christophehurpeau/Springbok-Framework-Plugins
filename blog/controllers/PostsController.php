<?php
class PostsController extends AController{
	/** */
	static function index(){
		set('posts',Post::QListAll()->paginate()->pageSize(10));
		render();
	}
	
	/** @ValidParams('/') @Required('slug') */
	static function tag($slug){
		$postTag=PostsTag::QOne()->addCondition('skmt.slug',$slug);
		if($postTag===false){
			$postTag=PostsTag::QOne()
				->innerjoin('PostsTagSlugRedirect',false,array('ptsr.new_slug=skmt.slug','ptsr.direct'=>true))
				->addCondition('ptsr.old_slug LIKE',$slug);
			
			if($postTag===false){
				$postTag=PostsTag::QOne()
						->innerjoin('SearchablesTermSlugRedirect',false,array('stsr.new_slug=skmt.slug','stsr.direct'=>true))
						->addCondition('stsr.old_slug LIKE',$slug);
			}
			notFoundIfFalse($postTag);
			redirectPermanent($postTag->link());
		}
		
		mset($postTag);
		set('posts',Post::QListAll()
			->withForce('PostTag')
			->addCondition('pt.tag_id',$postTag->id)
			->paginate()->pageSize(10));
		render();
	}
}