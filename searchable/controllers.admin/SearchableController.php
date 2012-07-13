<?php
Controller::$defaultLayout='admin/searchable';
/** @Check('ASecureAdmin') @Acl('Searchable') */
class SearchableController extends Controller{
	/** */
	function index(){
		Searchable::Table()->paginate()->actionClick('view')->render('Searchable');
	}
	/** */
	function keywords(){
		SearchablesKeyword::Table()->noAutoRelations()->fields('id,_type,created,updated')->with('MainTerm')
			->paginate()->controller('searchableKeyword')->actionClick('view')
			->fields(array('id','term','slug','_type','created','updated'))
			->render('Keywords');
	}
	
}