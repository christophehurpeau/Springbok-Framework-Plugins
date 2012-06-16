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
		SearchablesKeyword::Table()->paginate()->controller('searchableKeyword')->actionClick('view')->render('Keywords');
	}
	
}