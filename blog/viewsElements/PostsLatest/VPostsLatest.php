<?php
class VPostsLatest extends SViewCachedElement{
	protected static $views=array('view');
	
	/*#if DEV*/ public function exists(){ return false; } /*#/if*/
	
	public static function path(){return array('posts','latest-list');}
	public static function vars(){
		$posts=Post::QListAll()->addField('excerpt')->limit(/*#val blog.VPostsLatest.size */);
		foreach($posts as $post)
			$post->excerpt=UHtml::transformInternalLinks($post->excerpt,Config::$internalLinks,'index',/*#val blog.VPostsLatest.fullUrls */false);
		return array(
			'posts'=>$posts
		);
	}
}
