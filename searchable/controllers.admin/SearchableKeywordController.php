<?php
Controller::$defaultLayout='admin/searchable';
/** @Check('ASecureAdmin') @Acl('Searchable') */
class SearchableKeywordController extends Controller{
	/** @ValidParams('/searchable') @Id */
	function view($id){
		HBreadcrumbs::set(array('Keywords'=>'/searchable/keywords'));
		$keyword=SearchablesKeyword::ById($id)->with('MainTerm')->with('SearchablesTerm');
		notFoundIfFalse($keyword);
		mset($keyword);
		render();
	}
	
	
	/** @ValidParams @AllRequired @Id
	* keyword > @Valid('text') */
	function save(int $id,SearchablesKeyword $keyword){
		$keyword->id=$id;
		//foreach(array('slug','meta_title','meta_descr','meta_keywords') as $metaName)
		//	if(empty($keyword->$metaName)) $keyword->$metaName=$keyword->{'auto_'.$metaName}();
		/* IF(searchable.keywords.seo) */
		foreach(array('meta_title','meta_descr','meta_keywords') as $metaName)
			if(empty($keyword->$metaName)) $keyword->$metaName=null;
		/* /IF */
		$res=$keyword->update();
		//SearchableKeywordHistory::create($keyword,SearchableKeywordHistory::SAVE);
		renderText($res);
	}
	
	/** @ValidParams('/searchable') @Id @NotEmpty('term') */
	function autocomplete(int $id,$term){
		$keywordsTermsId=SearchablesKeywordTerm::QValues()->field('term_id')->byKeyword_id($id);
		$where=array('term LIKE'=>$term.'%');
		if(!empty($keywordsTermsId)) $where['id NOTIN']=$keywordsTermsId;
		self::renderJSON(json_encode(
			SearchablesTerm::QRows()->setFields(array('id','(term)'=>'name'))
				->where($where)->limit(14)));
	}
	
	/** @ValidParams('/searchable') @Id('id','termId') */
	function add(int $termId,int $id){
		if(SearchablesKeywordTerm::QInsert()->ignore()->set(array('term_id'=>$termId,'keyword_id'=>$id)))
			renderText('1');
	}
	/** @ValidParams('/searchable') @Id('id','termId') */
	function del(int $termId,int $id){
		if(SearchablesKeywordTerm::QDeleteOne()->where(array('term_id'=>$termId,'keyword_id'=>$id)))
			renderText('1');
	}
	/** @ValidParams('/searchable') @Id @NotEmpty('val') */
	function create(int $id,$val){
		//$termId=SearchablesTerm::QInsert()->set(array('term'=>SearchablesTerm::cleanTerm($val)));
		$term=new SearchablesTerm;
		$term->term=SearchablesTerm::cleanTerm($val);
		$term->type=SearchablesTerm::NONE;
		$term->insert();
		if(SearchablesKeywordTerm::QInsert()->set(array('term_id'=>$term->id,'keyword_id'=>$id)))
			renderText('1');
	}
}