<?php
/* IF(blog_personalizeAuthors_enabled) */
Controller::$defaultLayout='admin/blog';
/** @Check('ASecureAdmin') @Acl('Posts') */
class PostAuthorsController extends Controller{
	/** @Ajax @AllRequired */
	function add(int $author_id,int $post_id){
		renderText(PostAuthor::create($post_id,$author_id) ? '1' : '0');
	}
	/** @Ajax @AllRequired */
	function del(int $author_id,int $post_id){
		renderText(PostAuthor::deleteOneByAuthor_idAndPost_id($author_id,$post_id) ? '1' : '0');
	}
}
/* /IF */