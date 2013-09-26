<?php
Controller::$defaultLayout='admin/cms';
/** @Check('ACSecureAdmin') @Acl('Posts') */
class PostsCategoriesController extends Controller{
	/** */
	static function index(){
		HBreadcrumbs::set(array('Articles'=>'/posts'));
		PostsCategory::Table()->noAutoRelations()->fields('id')->with('MainTerm')->withParent('id,created,updated')->orderBy(array('ssk.created'))
			->allowFilters()
			->paginate()->controller('searchableKeyword')->actionClick('view')
			->fields(array('id','name','created','updated'))
			->render('Catégories',true);
	}
	
	/** @ValidParams('/postsCategories') @Required('postsCategory')
	* postsCategory > @Valid('name')
	*/ function add(PostsCategory $postsCategory){
		$postsCategory->insert();
		redirect('/postsCategories/edit/'.$postsCategory->id);
	}
	
	
	private static function _breadcrumbs(){
		HBreadcrumbs::set(array(
			'Articles'=>'/posts',
			'Catégories'=>'/postsCategories',
		));
	}
	
	/** @ValidParams('/postsCategories') @Required('id') */
	static function edit(int $id){
		self::_breadcrumbs();
		CRUD::edit('PostsCategory',$id);
	}
	
	/** @ValidParams('/postsCategories') @Required('id') */
	static function view(int $id){
		self::_breadcrumbs();
		CRUD::view('PostsCategory',$id,array(),array(
			'Post'=>Post::CRUDOptions()));
	}
}