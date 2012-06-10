<?php
Controller::$defaultLayout='admin/blog';
/** @Check('ASecureAdmin') @Acl('Posts') */
class PostsCategoriesController extends Controller{
	/** */
	function index(){
		HBreadcrumbs::set(array('Articles'=>'/posts'));
		
		PostsCategory::Table()/*->fields('id,name,created,updated')*/->orderBy('created')
			->allowFilters()
			->paginate()->setActionsRU()
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
	function edit(int $id){
		self::_breadcrumbs();
		CRUD::edit('PostsCategory',$id);
	}
	
	/** @ValidParams('/postsCategories') @Required('id') */
	function view(int $id){
		self::_breadcrumbs();
		CRUD::view('PostsCategory',$id,array(),array(
			'Post'=>array('title'=>'Articles','fields'=>'id,title,slug,status,created,published,updated','orderBy'=>array('created'=>'DESC'))));
	}
}