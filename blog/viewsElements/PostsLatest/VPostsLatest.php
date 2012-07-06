<?php
class VPostsLatest extends SViewCachedElement{
	/* DEV */ public function exists(){ return false; } /* /DEV */
	public static function path(){return DATA.'elementsCache/posts/latest-list';}
	public static function vars(){
		$posts=Post::QListAll()->addField('excerpt')->limit(4);
		foreach($posts as $post)
			$post->excerpt=UHtml::transformInternalLinks($post->excerpt,Config::$internalLinks);
		return array(
			'posts'=>$posts
		);
	}
}
