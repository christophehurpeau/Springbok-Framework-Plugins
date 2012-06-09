<?php
/** @Check('ASecureAdmin') */
class PostController extends Controller{
	/** */
	function index(){
		$table=Post::Table()->fields('id,title,slug,status,created,updated')->where(array('status !='=>Post::DELETED))->orderByCreated()
			->allowFilters()
			->paginate()->fields(array('id','title','status','created','updated'))->actionClick('edit')
			->render('Articles',true,'admin/blog');
	}
}