<?php
/* IF(blog_personalizeAuthors_enabled) */
Controller::$defaultLayout='admin/cms';
/** @Check('ASecureAdmin') @Acl('Posts') */
class PostsAuthorsController extends Controller{
	/** */
	function index(){
		HBreadcrumbs::set(array('Articles'=>'/posts'));
		PostsAuthor::Table()->fields('id,name,created,updated')->orderBy('created')
			->allowFilters()
			->paginate()->setActionsRU()
			->render('Auteurs','PostsAuthor');
	}
	
	/** @ValidParams('/postsAuthors') @Required('postsAuthor')
	* postsAuthor > @Valid('name') */
	function add(PostsAuthor $postsAuthor){
		$postsAuthor->insert();
		redirect('/postsAuthors/edit/'.$postsAuthor->id);
	}
	
	
	private static function _breadcrumbs(){
		HBreadcrumbs::set(array(
			'Articles'=>'/posts',
			'Auteurs'=>'/postsAuthors',
		));
	}
	
	/** @ValidParams('/postsAuthors') @Required('id') */
	function edit(int $id){
		self::_breadcrumbs();
		CRUD::edit('PostsAuthor',$id);
	}
	
	/** @ValidParams('/postsAuthors') @Required('id') */
	function view(int $id){
		self::_breadcrumbs();
		CRUD::view('PostsAuthor',$id,array(),array('Post'=>Post::CRUDOptions()));
	}
}
/* /IF */