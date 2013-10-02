<?php
/*#if blog_personalizeAuthors_enabled*/
Controller::$defaultLayout='admin/cms';
/** @Check('ACSecureAdmin') @Acl('Posts') */
class PostsAuthorsController extends Controller{
	/** */
	static function index(){
		HBreadcrumbs::set(array('Articles'=>'/posts'));
		PostsAuthor::Table()->fields('id,name,created,updated')->orderBy('created')
			->allowFilters()
			->paginate()->setActionsRU()
			->render('Auteurs','PostsAuthor');
	}
	
	/** @ValidParams('/postsAuthors') @Required('postsAuthor')
	* postsAuthor > @Valid('name') */
	static function add(PostsAuthor $postsAuthor){
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
	static function edit(int $id){
		self::_breadcrumbs();
		CRUD::edit('PostsAuthor',$id);
	}
	
	/** @ValidParams('/postsAuthors') @Required('id') */
	static function view(int $id){
		self::_breadcrumbs();
		CRUD::view('PostsAuthor',$id,array(),array('Post'=>Post::CRUDOptions()));
	}
}
/*#/if*/