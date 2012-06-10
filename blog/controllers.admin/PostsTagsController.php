<?php
Controller::$defaultLayout='admin/blog';
/** @Check('ASecureAdmin') @Acl('Posts') */
class PostsTagsController extends Controller{
	/** */
	function index(){
		HBreadcrumbs::set(array('Articles'=>'/posts'));
		PostsTag::Table()->fields('id,name,slug,slug_auto,created,updated')->orderBy('created')
			->allowFilters()
			->paginate()->setActionsRU()
			->render('Mots clé',true);
	}
	
	
	/** @ValidParams('/postsTags') @Required('postsTag')
	* postsTag > @Valid('name')
	*/ function add(PostsTag $postsTag){
		$postsTag->insert();
		redirect('/postsTags/edit/'.$postsTag->id);
	}
	
	
	private static function _breadcrumbs(){
		HBreadcrumbs::set(array(
			'Articles'=>'/posts',
			'Mots clé'=>'/postsTags',
		));
	}
	
	/** @ValidParams('/postsTags') @Required('id') */
	function edit(int $id){
		self::_breadcrumbs();
		CRUD::edit('PostsTag',$id);
	}
	
	/** @ValidParams('/postsTags') @Required('id') */
	function view(int $id){
		self::_breadcrumbs();
		CRUD::view('PostsTag',$id,array(),array('Post'=>array('title'=>'Articles','fields'=>'id,title,slug,status,created,published,updated','orderBy'=>array('created'=>'DESC'))));
	}
}