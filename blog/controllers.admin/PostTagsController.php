<?php
/** @Check('ACSecureAdmin') @Acl('Posts') */
class PostTagsController extends Controller{
	/** @Ajax @ValidParams @AllRequired */
	static function add(int $tag_id,int $post_id){
		renderText(PostTag::create($post_id,$tag_id) ? '1' : '0');
	}
	/** @Ajax @ValidParams @AllRequired
	* val > @MinLength(3) */
	static function create(int $post_id,$val){
		$tagId=PostsTag::create($val);
		renderText($tagId && PostTag::create($post_id,$tagId) ? $tagId : '0');
	}
	/** @Ajax @ValidParams @AllRequired */
	static function del(int $tag_id,int $post_id){
		$res=PostTag::deleteOneByTag_idAndPost_id($tag_id,$post_id);
		Post::onModified($post_id);
		renderText($res ? '1' : '0');
	}
}