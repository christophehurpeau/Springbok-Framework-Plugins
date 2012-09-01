<?php
/** @Check('ACSecureAdmin') @Acl('Posts') */
class PostCategoriesController extends Controller{
	/** @Ajax @AllRequired */
	function add(int $cat_id,int $post_id){
		$res=PostCategory::create($post_id,$cat_id);
		Post::onModified($post_id);
		renderText($res ? '1' : '0');
	}
	
	/** @Ajax @AllRequired */
	function del(int $cat_id,int $post_id){
		$res=PostCategory::deleteOneByCategory_idAndPost_id($cat_id,$post_id);
		Post::onModified($post_id);
		renderText($res ? '1' : '0');
	}
}