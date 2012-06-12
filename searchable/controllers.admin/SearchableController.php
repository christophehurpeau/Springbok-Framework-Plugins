<?php
/** @Check('ASecureAdmin') @Acl('Searchable') */
class SearchableController extends Controller{
	/** */
	function index(){
		SearchableKeyword::Table()->paginate()->actionClick('keyword')->render('Keywords');
	}
	
	/** @ValidParams('/searchable') @Required('id') */
	function keyword($id){
		HBreadcrumbs::set(array('Keywords'=>'/searchable'));
		$keyword=SearchableKeyword::ById($id)->with('SearchableTerm','id,term');
		notFoundIfFalse($keyword);
		mset($keyword);
		render();
	}
	
	/** @ValidParams('/searchable') @Required('id','term') */
	function autocomplete(int $id,$term){
		$keywordsTermsId=SearchableKeywordTerm::QValues()->field('term_id')->byKeyword_id($id);
		self::renderJSON(json_encode(
			SearchableTerm::QRows()->setFields(array('id','(term)'=>'name'))
				->where(array('term LIKE'=>$term.'%','id NOTIN'=>$keywordsTermsId))->limit(20)));
	}
	
	/** @ValidParams('/searchable') @Required('id','termId') */
	function add(int $termId,int $id){
		if(SearchableKeywordTerm::QInsert()->ignore()->set(array('term_id'=>$termId,'keyword_id'=>$id)))
			renderText('1');
	}
	/** @ValidParams('/searchable') @Required('id','termId') */
	function del(int $termId,int $id){
		if(SearchableKeywordTerm::QDeleteOne()->where(array('term_id'=>$termId,'keyword_id'=>$id)))
			renderText('1');
	}
	/** @ValidParams('/searchable') @Required('id','val') */
	function create(int $id,$val){
		$termId=SearchableTerm::QInsert()->set(array('term'=>SearchableTerm::cleanTerm($val)));
		if(SearchableKeywordTerm::QInsert()->set(array('term_id'=>$termId,'keyword_id'=>$id)))
			renderText('1');
	}
}