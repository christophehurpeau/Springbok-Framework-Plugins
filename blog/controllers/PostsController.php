<?php
class PostsController extends AController{
	/** */
	function index(){
		set('posts',CPagination::create(Post::QListAll())->pageSize(10)->execute());
		render();
	}
	
	/** @ValidParams('/') @Required('slug') */
	function tag($slug,int $id){
		$postTag=PostsTag::QOne()->with('MainTerm')->addCondition('skmt.slug',$slug);
		if($postTag===false){
			if($id!==null){
				$postTag=PostsTag::QOne()->with('MainTerm')
					->addCondition('id',$id);
			}else{
				$postTag=PostsTag::QOne()->with('MainTerm')
						->innerjoin('SearchablesTermSlugRedirect',false,array('stsr.new_slug=skmt.slug','stsr.direct'=>true))
						->addCondition('stsr.old_slug LIKE',$slug);
			}		
			notFoundIfFalse($postTag);
			redirectPermanent($postTag->link());
		}
		if($id!==null && $postTag->id != $id) notFound();
		
		mset($postTag);
		set('posts',CPagination::create(Post::QListAll()
			->with('PostTag',array('join'=>true,'fields'=>false))
			->addCondition('pt.tag_id',$postTag->id))->pageSize(10)->execute());
		render();
	}
}