<?php
class VPostsTags extends SViewCachedElement{
	/*#if DEV*/ public function exists(){ return false; } /*#/if*/
	
	public static function path(){return DATA.'cache/posts-tags-list';}
	public static function vars(){
		return array(
			'tags'=>PostsTag::findAllSize()
		);
	}
}
