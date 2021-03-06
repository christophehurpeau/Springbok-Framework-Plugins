<?php
class PostController extends AController{
	/** @ValidParams('/') @Id('id') @NotEmpty('slug') */
	static function view(int $id,$slug){
		$post=Post::QOne()->fields('id')->withParent('name,slug')->where(/*#if blog_slugOnly_enabled*/$id===null ? array('slug'=>$slug) :/*#/if*/array('id'=>$id))
			->addCondition('status',Post::PUBLISHED)->mustFetch();
		if(/*#if blog_slugOnly_enabled*/$id!==null && /*#/if*/$post->slug!==$slug) redirectPermanent($post->link());

		/*#if blog_comments_enabled*/
		$userId=CSecure::connected();
		$pagination=$post->findWithPaginate('PostComment',PostComment::_paginationQueryCommentsOptions($userId));
		$pagination->pageSize(5)->page(1)->execute();
		/*#/if*/
		
		$ve=VPost::create($id);
		set('metas',$ve->metas());
		mset($post,$ve);
		self::_beforeRenderPost($post);
		render();
	}
	
	public static function _beforeRenderPost(&$post){}
	
	/*#if blog_comments_enabled*/
	/** @Ajax
	* postId > @Required
	* page > @Required
	*/ function commentsPagination(int $postId,int $page,boolean $forceLastPage){
		$query=PostComment::QAll()->where(array('post_id'=>$postId));
		set('userId',$userId=CSecure::connected());
		$queryOptions=PostComment::_paginationQueryCommentsOptions($userId);
		$query->setAllWith($queryOptions['with']); $query->where($queryOptions['where']);
		$pagination=CPagination::_create($query)->pageSize(5)->page($page)->execute(); /* Don't forget lgs_post.js ! */
		
		if($forceLastPage!==null && $forceLastPage){
			if(($totalPages=$pagination->getTotalPages()) !== $page) $pagination->refindResults($totalPages);
		}
		set('comments',$pagination);
		render('_comments');
	}
	/*#/if*/
	
	/** @Ajax @Check
	* postId > @Required
	*/ function comment(int $postId,int $rating,PostComment $comment){
	}
}