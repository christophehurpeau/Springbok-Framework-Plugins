<?php
class PostController extends AController{
	/** @ValidParams('/') @Required('slug')
	* slug > @MinLength(2)
	*/ function view(int $id,$slug){
		$post=Post::QOne()->fields('title,slug')->where(/* IF(blog_slugOnly_enabled) */$id===null ? array('slug'=>$slug) :/* /IF */array('id'=>$id));
		notFoundIfFalse($post);
		if(/* IF(blog_slugOnly_enabled) */$id!==null && /* /IF*/$post->slug!==$slug) redirect($post->link());

		/* IF(blog_comments_enabled) */
		$userId=CSecure::connected();
		$pagination=$post->findWithPaginate('PostComment',PostComment::_paginationQueryCommentsOptions($userId));
		$pagination->pageSize(5)->page(1)->execute();
		/* /IF */
		
		$ve=VPost::create($id);
		set('metas',$ve->metas());
		mset($post,$ve);
		render();
	}
	
	/* IF(blog_comments_enabled) */
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
	/* /IF */
	
	/** @Ajax @Check
	* postId > @Required
	*/ function comment(int $postId,int $rating,PostComment $comment){
	}
}