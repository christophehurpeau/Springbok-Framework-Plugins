<?php
class VPost extends SViewCachedElement{
	protected static $views=array('view','tags','metas');
	
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
		$post->content=UHtml::transformInternalLinks($post->excerpt,array(
			'post'=>function($id){ $post=new Post; $post->id=$id;
					$post->slug=Searchable::QValue()->field('slug')->with('Post',array('fields'=>false))->addCondition('p.id',$id); return $post->link(); },
			'postsTag'=>function($id){ $tag=new PostsTag; $tag->id=$id;
					$tag->slug=SearchablesKeyword::QValue()->field('slug')->with('PostsTag',array('fields'=>false))->addCondition('t.id',$id); return $tag->link(); },
		));
		
		return array('post'=>$post);
	}
	public function metas(){
		return json_decode($this->read('metas'),true);
	}
}