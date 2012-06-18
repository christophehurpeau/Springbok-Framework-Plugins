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
		SearchablesKeyword::Table()->fields('id,name,slug,meta_title,_type,created,updated')
			->paginate()->controller('searchableKeyword')->actionClick('view')->render('Keywords');
	}
	
}