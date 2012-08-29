<?php
Controller::$defaultLayout='admin/cms';
/** @Check('ASecureAdmin') @Acl('Posts') */
class PostsTagsController extends Controller{
	/** */
	function index(){
		HBreadcrumbs::set(array('Articles'=>'/posts'));
		PostsTag::Table()->noAutoRelations()->fields('id')->with('MainTerm')->withParent('id,created,updated')->orderBy(array('ssk.created'))
			->allowFilters()
			->paginate()->controller('searchableKeyword')->actionClick('view')
			->fields(array('id','name','slug','created','updated'))
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
		render();
	}
	
	/** @ValidParams('/postsTags') @Id('id') */
	function view(int $id){
		self::_breadcrumbs();
		CRUD::view('PostsTag',$id,array(),array('Post'=>Post::CRUDOptions()));
	}
	
	
	/** @Ajax @ValidParams @NotEmpty('term') */
	function autocomplete($term){
		self::renderJSON(SModel::json_encode(
			PostsTag::QAll()->field('id')->with('MainTerm')
				->where(array('skmt.term LIKE'=>'%'.$term.'%'))
				->limit(14)
			,'_adminAutocomplete'
		));
	}

	/** @Ajax @ValidParams @NotEmpty('val') */
	function checkId(int $val){
		$tag=PostsTag::QOne()->field('id')->byId($val);
		self::renderJSON($tag===false?'{"error":"Tag inconnu"}':$tag->toJSON_adminAutocomplete());
	}
	
}