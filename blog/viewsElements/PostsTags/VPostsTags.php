<?php
class VPostsTags extends SViewCachedElement{
	/* DEV */ public function exists(){ return false; } /* /DEV */
	
	public static function path(){return DATA.'cache/posts-tags-list';}
	public static function vars(){
		return array(
			'tags'=>PostsTag::findAllSize()
		);
	}
}
