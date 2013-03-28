<?php
Controller::$defaultLayout='admin/searchable';
/** @Check('ACSecureAdmin') @Acl('Searchable') */
class SearchableController extends Controller{
	/** */
	function index(){
		Searchable::Table()->allowFilters()->paginate()->actionView()->render('Searchable');
	}
	
	/** */
	function view(int $id){
		HBreadcrumbs::set(array('Searchables'=>'/searchable'));
		$table=Searchable::TableOne()->byId($id)->end();
		if(!$table->hasResult()) exit("Aucun résultat trouvé");
		$tableWords=$table->rel('SearchablesWord')->noAutoRelations()//->belongsToFields(array('insee'=>'City'))
			->fields('id,word,length')->paginate()->actionClick('/searchableWord/view')->actionView();
		$tableKeywords=$table->rel('SearchablesKeyword')->paginate()->actionClick('/searchableKeyword/view')->actionView();
		$sb=$table->getResult();
		mset($sb,$table,$tableWords,$tableKeywords);
		render();
	}
	
	/** */
	function reindex(int $id){
		$sb=Searchable::ById($id)->notFoundIfFalse();
		$sb->reindex();
		redirect('/searchable/view/'.$id);
	}
	
	/** */
	function renormalize(int $id){
		$sb=Searchable::ById($id)->notFoundIfFalse();
		$sb->_renormalize();
		redirect('/searchable/view/'.$id);
	}
	
	/** */
	function keywords(){
		SearchablesKeyword::Table()->noAutoRelations()->fields('id,_type,created,updated')
			->with('MainTerm','term,slug')
			->allowFilters()->paginate()->controller('searchableKeyword')->actionView()
			->fields(array('id','term','slug','_type','created','updated'))
			->render('Keywords');
	}
	
	/** */
	function terms(){
		SearchablesTerm::Table()->noAutoRelations()->fields('id,term,slug,created,updated')
			->allowFilters()->paginate()->controller('searchableTerm')->actionView()
			->fields(array('id','term','slug','created','updated'))
			->render('Terms');
	}
	
}