<?php
class VPostsLatest extends SViewCachedElement{
	public static function path(){return DATA.'cache/posts-latest-list';}
	public static function vars(){
		return array(
			'posts'=>Post::QAll()->byStatus(Post::PUBLISHED)
				->fields('id,title,slug,excerpt,created,published,updated')
				->orderByCreated()
				/*->with('PostsAuthor','name,url')*/
				->limit(4)
				->execute()
		);
	}
}
