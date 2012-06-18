<?php
Controller::$defaultLayout='admin/searchable';
/** @Check('ASecureAdmin') @Acl('Searchable') */
class SearchableKeywordController extends Controller{
	/** */
	function autoEveryKeywords(){
		SearchablesKeyword::autoEveryKeywords();
	}
	
	/** @ValidParams('/searchable') @Required('id') */
	function view($id){
		HBreadcrumbs::set(array('Keywords'=>'/searchable/keywords'));
		$keyword=SearchablesKeyword::ById($id)->with('SearchablesTerm','id,term');
		notFoundIfFalse($keyword);
		mset($keyword);
		render();
	}
	
	
	/** @ValidParams @AllRequired
	* keyword > @Valid('descr') */
	function save(int $id,SearchablesKeyword $keyword){
		$keyword->id=$id;
		foreach(array('slug','meta_title','meta_descr','meta_keywords') as $metaName)
			if(empty($keyword->$metaName)) $keyword->$metaName=$keyword->{'auto_'.$metaName}();
		$res=$keyword->update();
		//SearchableKeywordHistory::create($keyword,SearchableKeywordHistory::SAVE);
		renderText($res);
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
		//$termId=SearchablesTerm::QInsert()->set(array('term'=>SearchablesTerm::cleanTerm($val)));
		$term=new SearchablesTerm;
		$term->term=SearchablesTerm::cleanTerm($val);
		$term->insert();
		if(SearchablesKeywordTerm::QInsert()->set(array('term_id'=>$term->id,'keyword_id'=>$id)))
			renderText('1');
	}
}