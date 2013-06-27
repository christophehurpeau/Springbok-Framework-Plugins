<?php
class VPost extends SViewCachedElement{
	protected static $views=array('view','tags','metas','excerpt');
	
	/*#if DEV */public function exists(){ return false; }/*#/if*/
	
	public static function path($id){return array('posts',$id);}
	
	public static function vars($id){
		$post=Post::QOne()->withParent()->where(array('id'=>$id))
			/*#if blog_ratings_enabled*/->with('Rating')/*#/if*/
			->with('Post',Post::withOptions(array('where'=>array('p.status'=>Post::PUBLISHED))))
			->with('PostImage',array('fields'=>'image_id','onConditions'=>array('in_text'=>true)))
			->with('PostsTag',PostsTag::withOptions())
			/*#if blog_comments_enabled*/->with('PostComment',array('where'=>array('status'=>PostComment::VALID)))/*#/if*/
			/*#if blog_personalizeAuthors_enabled*/->with('PostsAuthor','name,url')/*#/if*/;
		$post->content=UHtml::transformInternalLinks($post->content,Config::$internalLinks,'index',false);
		/*#if blog.postView.addGoogleAdInMiddle*/
		$nbP=substr_count($post->content,'<p>');
		if(!empty($nbP) && $nbP > 2){
			$midP=floor($nbP/2);
			$i=0; $curPos=0;
			while($i++<$midP) $curPos=strpos($post->content,'</p>',$curPos+1);
			$post->content=substr($post->content,0,$curPos).'<?php echo ACGoogleAds::midPost() ?>'.substr($post->content,$curPos);
		}
		/*#/if*/
		$post->excerpt=UHtml::transformInternalLinks($post->excerpt,Config::$internalLinks,'index',false);
		
		return array('post'=>$post);
	}
	public function metas(){
		return json_decode($this->_store->read('metas'),true);
	}
}