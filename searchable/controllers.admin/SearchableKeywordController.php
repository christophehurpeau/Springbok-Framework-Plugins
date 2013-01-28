<?php
Controller::$defaultLayout='admin/searchable';
/** @Check('ACSecureAdmin') @Acl('Searchable') */
class SearchableKeywordController extends Controller{
	/** @ValidParams('/searchable') @Id */
	function view($id){
		HBreadcrumbs::set(array('Keywords'=>'/searchable/keywords'));
		$keyword=SearchablesKeyword::ById($id)->with('MainTerm')
				->with('TermWithType')
				->with('Types')
				->notFoundIfFalse();
						/*->with('SearchablesTypedTerm',array('with'=>array('SearchablesTerm'=>array('fieldsInModel'=>true,'fields'=>'term'))))*/
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
	
	/** @ValidParams('/searchable') @Id('id','termId') @NotEmpty('type')*/
	function add(int $id,int $termId,int $type,int $proximity){
		SearchablesKeywordTerm::add($id,$termId,$type,$proximity);
		$t=SearchablesKeywordTerm::byPks($id,$termId);
		renderJSON('{"id":'.$termId.',"html":'.json_encode($t->nameHtml()).'}');
	}
	/** @ValidParams('/searchable') @Id('id','termId') */
	function del(int $termId,int $id){
		if(SearchablesKeywordTerm::QDeleteOne()->where(array('term_id'=>$termId,'keyword_id'=>$id)))
			renderText('1');
	}
	/** @ValidParams('/searchable') @Id @NotEmpty('name','type') */
	function create(int $id,$name,int $type,int $proximity){
		if(($termId=SearchablesKeyword::addTerm($id,$name,$proximity,$type))){
			$t=SearchablesKeywordTerm::byPks($id,$termId);
			renderJSON('{"ok":1,"html":'.json_encode($t->nameHtml()).'}');
		}
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
	
	/** @ValidParams('/searchable') @Id('id','termId') @NotEmpty('type') */
	function edit(int $id,int $termId,int $type,int $proximity){
		$t=SearchablesKeywordTerm::byPks($id,$termId);
		notFoundIfFalse($t);
		if($t->type !== $type){
			$t->type=$type;
			SearchablesTypedTerm::addIgnore($termId,$type);
		}
		$t->proximity=$proximity;
		if(isset($_GET['isKeyword'])) $t->inherited_from=$_GET['isKeyword']==='1' ? $termId : null;
		else $t->inherited_from=null;
		$t->update('type','proximity','inherited_from');
		renderJSON('{"ok":1,"html":'.json_encode($t->nameHtml()).'}');
	}
}