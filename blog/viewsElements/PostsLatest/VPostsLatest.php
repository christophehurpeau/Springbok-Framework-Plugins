<?php
class VPostsLatest extends SViewCachedElement{
	protected static $views=array('view','titles');
	
	/* DEV */ public function exists(){ return false; } /* /DEV */
	public static function path(){return DATA.'elementsCache/posts/latest-list';}
	public static function vars(){
		$posts=Post::QListAll()->addField('excerpt')->limit(/* VALUE(blog.VPostsLatest.size) */);
		foreach($posts as $post)
			$post->excerpt=UHtml::transformInternalLinks($post->excerpt,Config::$internalLinks,'index',/* VALUE(blog.VPostsLatest.fullUrls) *//* HIDE */false/* /HIDE */);
		return array(
			'posts'=>$posts
		);
	}
}
