<?php
/** @Check('ASecureAdmin') @Acl('Posts') */
class PostCategoriesController extends Controller{
	/** @Ajax @AllRequired */
	function add(int $cat_id,int $post_id){
		renderText(PostCategory::create($post_id,$cat_id) ? '1' : '0');
	}
	
	/** @Ajax @AllRequired */
	function del(int $cat_id,int $post_id){
		renderText(PostCategory::deleteOneByCategory_idAndPost_id($cat_id,$post_id) ? '1' : 0);
	}
}