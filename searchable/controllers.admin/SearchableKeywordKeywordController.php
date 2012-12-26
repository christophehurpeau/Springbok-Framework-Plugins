<?php
Controller::$defaultLayout='admin/searchable';
/** @Check('ACSecureAdmin') @Acl('Searchable') */
class SearchableKeywordKeywordController extends Controller{
	/** @ValidParams('/searchable') @Id @NotEmpty('term') */
	function autocomplete(int $id,$term){
		$keywordsId=SearchablesKeywordKeyword::QValues()->field('keyword_id')->byKeyword_id($id);
		$keywordsId[]=$id;
		$where=array('skmt.term LIKE'=>$term.'%','id NOTIN'=>$keywordsId);
		self::renderJSON(json_encode(
			SearchablesKeyword::QRows()->with('MainTerm',false)->setFields(array('id','(term)'=>'name'))->where($where)->limit(14)));
	}
	
	/** @ValidParams('/searchable') @Id('id','keywordId') */
	function add(int $keywordId,int $id){
		if(SearchablesKeywordKeyword::add($id,$keywordId));
			renderText('1');
	}
	/** @ValidParams('/searchable') @Id('id','keywordId') */
	function del(int $termId,int $id){
		if(SearchablesKeywordKeyword::del($id,$keywordId))
			renderText('1');
	}
}