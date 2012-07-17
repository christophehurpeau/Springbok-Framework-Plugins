<?php
class VPost extends SViewCachedElement{
	protected static $views=array('view','tags','metas','excerpt');
	
	/* DEV */ public function exists(){ return false; } /* /DEV */
	
	public static function path($id){return DATA.'elementsCache/posts/'.$id;}
	
	public static function vars($id){
		$post=Post::QOne()->withParent()->where(array('id'=>$id))
			/* IF(blog_ratings_enabled) */->with('Rating')/* /IF */
			->with('Post',Post::withOptions(array('where'=>array('p.status'=>Post::PUBLISHED))))
			->with('PostImage',array('fields'=>'image_id','onConditions'=>array('in_text'=>true)))
			->with('PostsTag',PostsTag::withOptions())
			/* IF(blog_comments_enabled) */->with('PostComment',array('where'=>array('status'=>PostComment::VALID)))/* /IF */
			/* IF(blog_personalizeAuthors_enabled) */->with('PostsAuthor','name,url')/* /IF */;
		$post->content=UHtml::transformInternalLinks($post->content,Config::$internalLinks,'index',false);
		$post->excerpt=UHtml::transformInternalLinks($post->excerpt,Config::$internalLinks,'index',false);
		
		return array('post'=>$post);
	}
	public function metas(){
		return json_decode($this->read('metas'),true);
	}
}