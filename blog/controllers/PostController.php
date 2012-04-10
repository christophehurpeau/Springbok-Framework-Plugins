<?php
class PostController extends AController{
	/** */
	function index(){
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
		$pagination=$post->findWithPaginate('UserComment',UserComment::_paginationQueryCommentsOptions($userId));
		$pagination->pageSize(5)->page(1)->execute();
		
		if($userId===false) $userHasRating=false;
		else{
			//$guestHasComment=$pagination->getTotalResults() > 0 ? PostComment::exist($guestId,$id) : false;
			$userHasRating=/*$guestHasComment ? PostRating::exist($guestId,$id) : */UserRating::ratingPostValue($userId,$id);
		}
		mset($post,$userId,$userHasRating);
		set('visitCode',PageVisit::visitPost($id,AConsts::LGS));
		render();
	}
	
	/** @Ajax
	* postId > @Required
	* page > @Required
	*/ function commentsPagination(int $postId,int $page,boolean $forceLastPage){
		$query=UserComment::QAll()->where(array('about_type'=>AConsts::POST,'about_id'=>&$postId));
		set('userId',$userId=CSecure::connected());
		$queryOptions=UserComment::_paginationQueryCommentsOptions($userId);
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
	*/ function rating(int $postId,int $rating,UserComment $userComment){
		self::addRating(AConsts::POST,$postId,$rating,$userComment);
	}
}