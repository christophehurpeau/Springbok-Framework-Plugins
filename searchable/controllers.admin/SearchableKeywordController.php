<?php
Controller::$defaultLayout='admin/searchable';
/** @Check('ACSecureAdmin') @Acl('Searchable') */
class SearchableKeywordController extends Controller{
	/** @ValidParams('/searchable') @Id */
	function view($id){
		HBreadcrumbs::set(array('Keywords'=>'/searchable/keywords'));
		$keyword=SearchablesKeyword::ById($id)->with('MainTerm')->with('TermWithType')->with('Types');
						/*->with('SearchablesTypedTerm',array('with'=>array('SearchablesTerm'=>array('fieldsInModel'=>true,'fields'=>'term'))))*/
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
		$where=array('st.term LIKE'=>$term.'%');
		if(!empty($keywordsTermsId)) $where['term_id NOTIN']=$keywordsTermsId;
		self::renderJSON(json_encode(
			SearchablesTypedTerm::QAll()->withField('SearchablesTerm','term')->where($where)->limit(16)));
	}
	
	/** @ValidParams('/searchable') @Id('id') @NotEmpty('termId') */
	function add($termId,int $id){
		list($termId,$type)=explode('-',$termId);
		if(empty($termId) || empty($type) || !($termId=(int)$termId) || !($type=(int)$type)) notFound();
		
		if(SearchablesKeywordTerm::add($id,$termId,$type))
			renderText('1');
	}
	/** @ValidParams('/searchable') @Id('id','termId') */
	function del(int $termId,int $id){
		if(SearchablesKeywordTerm::QDeleteOne()->where(array('term_id'=>$termId,'keyword_id'=>$id)))
			renderText('1');
	}
	/** @ValidParams('/searchable') @Id @NotEmpty('name','type') */
	function create(int $id,$name,int $type){
		if(SearchablesKeyword::addTerm($id,$name,$type))
			renderText('1');
		/*
		//$termId=SearchablesTerm::QInsert()->set(array('term'=>SearchablesTerm::cleanTerm($val)));
		$term=new SearchablesTerm;
		$term->term=SearchablesTerm::cleanTerm($val);
		$term->type=SearchablesTypedTerm::NONE;
		$term->insert();
		if(SearchablesKeywordTerm::QInsert()->set(array('term_id'=>$term->id,'keyword_id'=>$id)))
			renderText('1');
		 */
	}
}