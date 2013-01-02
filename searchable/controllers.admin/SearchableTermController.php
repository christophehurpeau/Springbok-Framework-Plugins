<?php
Controller::$defaultLayout='admin/searchable';
/** @Check('ACSecureAdmin') @Acl('Searchable') */
class SearchableTermController extends Controller{
	/** @ValidParams('/searchable') @Id */
	function view($id){
		HBreadcrumbs::set(array('Terms'=>'/searchable/terms'));
		$term=SearchablesTerm::ById($id)->with('SearchablesKeyword',array('fields'=>'id','with'=>array('MainTerm')))->with('Types');
		notFoundIfFalse($term);
		mset($term);
		render();
	}
	
	/** @ValidParams @AllRequired @Id
	* keyword > @Valid('descr') */
	function save(int $id,SearchablesTerm $term){
		$term->id=$id;
		//foreach(array('slug','meta_title','meta_descr','meta_keywords') as $metaName)
		//	if(empty($keyword->$metaName)) $keyword->$metaName=$keyword->{'auto_'.$metaName}();
		foreach(array('meta_title','meta_descr','meta_keywords') as $metaName)
			if(empty($term->$metaName)) $term->$metaName=null;
		$res=$term->update();
		//SearchableKeywordHistory::create($keyword,SearchableKeywordHistory::SAVE);
		renderText($res);
	}
	
	/*
	/** @ValidParams('/searchable') @Id @NotEmpty('term') * /
	private function autocomplete(int $id,$term){
		$termsKeywordsId=SearchablesKeywordTerm::QValues()->field('keyword_id')->byTerm_id($id);
		$where=array('skmt.term LIKE'=>$term.'%');
		if(!empty($termsKeywordsId)) $where['id NOTIN']=$termsKeywordsId;
		self::renderJSON(json_encode(
			SearchablesKeyword::QRows()->with('MainTerm',false)->setFields(array('id','(term)'=>'name'))->where($where)->limit(14)));
	}
	
	/** @ValidParams('/searchable') @Id('id','keywordId') * /
	private function add(int $keywordId,int $id){
		if(SearchablesKeywordTerm::QInsert()->ignore()->set(array('term_id'=>$id,'keyword_id'=>$keywordId)))
			renderText('1');
	}
	/** @ValidParams('/searchable') @Id('id','keywordId') * /
	private function del(int $keywordId,int $id){
		if(SearchablesKeywordTerm::QDeleteOne()->where(array('term_id'=>$id,'keyword_id'=>$keywordId)))
			renderText('1');
	}*/
}