<?php
Controller::$defaultLayout='admin/cms';
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
	
	/** @ValidParams('/postsTags') @Id('id') */
	function edit(int $id){
		self::_breadcrumbs();
		CRUD::edit('PostsTag',$id);
	}
	
	/** @ValidParams('/postsTags') @Id('id') */
	function view(int $id){
		self::_breadcrumbs();
		CRUD::view('PostsTag',$id,array(),array('Post'=>Post::CRUDOptions()));
	}
	
	
	/** @Ajax @ValidParams @NotEmpty('term') */
	function autocomplete($term){
		self::renderJSON(SModel::json_encode(
			PostsTag::QAll()->field('id')->withParent('name,slug')
				->where(array('ssk.name LIKE'=>'%'.$term.'%'))
				->limit(14)
			,'_adminAutocomplete'
		));
	}

	/** @Ajax @ValidParams @NotEmpty('val') */
	function checkId(int $val){
		$tag=PostsTag::QOne()->field('id')->withParent('name,slug')->byId($val);
		self::renderJSON($tag===false?'{"error":"Tag inconnu"}':$tag->toJSON_adminAutocomplete());
	}
	
}