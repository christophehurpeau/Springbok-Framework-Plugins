<?php
/* IF(blog_personalizeAuthors_enabled) */
Controller::$defaultLayout='admin/cms';
/** @Check('ASecureAdmin') @Acl('Posts') */
class PostAuthorsController extends Controller{
	/** @Ajax @AllRequired */
	function add(int $author_id,int $post_id){
		$res=PostAuthor::create($post_id,$author_id);
		Post::onModified($post_id);
		renderText($res ? '1' : '0');
	}
	/** @Ajax @AllRequired */
	function del(int $author_id,int $post_id){
		$res=PostAuthor::deleteOneByAuthor_idAndPost_id($author_id,$post_id);
		Post::onModified($post_id);
		renderText($res ? '1' : '0');
	}
}
/* /IF */