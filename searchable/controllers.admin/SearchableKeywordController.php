<?php
/** @Check('ASecureAdmin') @Acl('Searchable') */
class SearchableKeywordController extends Controller{
	
	/** @ValidParams('/searchable') @Required('id') */
	function view($id){
		HBreadcrumbs::set(array('Keywords'=>'/searchableKeyword'));
		$keyword=SearchablesKeyword::ById($id)->with('SearchablesTerm','id,term');
		notFoundIfFalse($keyword);
		mset($keyword);
		render();
	}
	
	/** @ValidParams('/searchable') @Required('id','term') */
	function autocomplete(int $id,$term){
		$keywordsTermsId=SearchablesKeywordTerm::QValues()->field('term_id')->byKeyword_id($id);
		self::renderJSON(json_encode(
			SearchablesTerm::QRows()->setFields(array('id','(term)'=>'name'))
				->where(array('term LIKE'=>$term.'%','id NOTIN'=>$keywordsTermsId))->limit(20)));
	}
	
	/** @ValidParams('/searchable') @Required('id','termId') */
	function add(int $termId,int $id){
		if(SearchablesKeywordTerm::QInsert()->ignore()->set(array('term_id'=>$termId,'keyword_id'=>$id)))
			renderText('1');
	}
	/** @ValidParams('/searchable') @Required('id','termId') */
	function del(int $termId,int $id){
		if(SearchablesKeywordTerm::QDeleteOne()->where(array('term_id'=>$termId,'keyword_id'=>$id)))
			renderText('1');
	}
	/** @ValidParams('/searchable') @Required('id','val') */
	function create(int $id,$val){
		$termId=SearchablesTerm::QInsert()->set(array('term'=>SearchablesTerm::cleanTerm($val)));
		if(SearchablesKeywordTerm::QInsert()->set(array('term_id'=>$termId,'keyword_id'=>$id)))
			renderText('1');
	}
}