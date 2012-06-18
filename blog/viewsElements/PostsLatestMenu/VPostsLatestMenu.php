<?php
class VPostsLatestMenu extends SViewCachedElement{
	/* DEV */ public function exists(){ return false; } /* /DEV */
	public static function path(){return DATA.'elementsCache/posts/latest-menu-list';}
	public static function vars(){
		return array(
			'posts'=>Post::QAll()->byStatus(Post::PUBLISHED)
				->fields('id')->withParent('name,slug')
				->orderBy(array('sb.created'=>'DESC'))
				/*->with('PostsAuthor','name,url')*/
				->limit(6)
				->execute()
		);
	}
}
