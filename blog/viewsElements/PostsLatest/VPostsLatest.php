<?php
class VPostsLatest extends SViewCachedElement{
	/* DEV */ public function exists(){ return false; } /* /DEV */
	public static function path(){return DATA.'elementsCache/posts/latest-list';}
	public static function vars(){
		return array(
			'posts'=>Post::QListAll()->limit(4)->execute()
		);
	}
}
