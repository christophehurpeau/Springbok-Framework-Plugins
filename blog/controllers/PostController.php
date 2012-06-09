<?php
class PostController extends AController{
	/** */
	function index(){
		set('posts',CPagination::create(Post::QAll()->fields('id,title,slug,intro,created,published,updated')
						->with('PostImage','image_id')->orderByCreated())->pageSize(10)->execute());
		render();
	}
	/** @ValidParams('/') @CachedFor('10m')
	* id > @Required
	* slug > @Required
	*/ function view(int $id,$slug){
		$post=Post::QOne()->byId($id)
			->with('Rating')
			->with('Post','id,title,slug')
			->with('PostImage',array('fields'=>'image_id','onConditions'=>array('in_text'=>true)))
			->with('PostsAuthor','name,url');
		notFoundIfFalse($post);
		if($post->slug!==$slug) redirect($post->link());

		$userId=CSecure::connected();
		$pagination=$post->findWithPaginate('PostComment',PostComment::_paginationQueryCommentsOptions($userId));
		$pagination->pageSize(5)->page(1)->execute();
		
		mset($post);
		render();
	}
	
	/** @Ajax
	* postId > @Required
	* page > @Required
	*/ function commentsPagination(int $postId,int $page,boolean $forceLastPage){
		$query=PostComment::QAll()->where(array('post_id'=>&$postId));
		set('userId',$userId=CSecure::connected());
		$queryOptions=PostComment::_paginationQueryCommentsOptions($userId);
		$query->setAllWith($queryOptions['with']); $query->where($queryOptions['where']);
		$pagination=CPagination::create($query)->pageSize(5)->page($page)->execute(); /* Don't forget lgs_post.js ! */
		
		if($forceLastPage!==null && $forceLastPage){
			if(($totalPages=$pagination->getTotalPages()) !== $page) $pagination->refindResults($totalPages);
		}
		set('comments',$pagination);
		render('_comments');
	}

	
	/** @Ajax @Check
	* postId > @Required
	*/ function comment(int $postId,int $rating,PostComment $comment){
	}
}