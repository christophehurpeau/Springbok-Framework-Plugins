<?php
/** @Check('ACSecureAdmin') @Acl('CMS') */
class CmsMenuController extends Controller{
	/** */
	static function index(){
		set('menu',CmsMenu::QList()->noFields()->with('Page','id,name')->addCondition('pg.status !=',Page::DELETED)->fetch());
		render();
	}
	
	
	/** @Ajax @ValidParams @Required('term') */
	static function autocomplete($term){
		$pagesIgnoreIds=CmsMenu::QValues()->field('page_id')->fetch();
		$where=array('name LIKE'=>'%'.$term.'%','status !='=>Page::DELETED);
		if(!empty($pagesIgnoreIds)) $where['id NOTIN']=$pagesIgnoreIds;
		self::renderJSON(SModel::json_encode(
			Page::QAll()->fields('id,name,slug')->where($where)->limit(14)->fetch()
			,'_autocompleteSimple'
		));
	}
	
	/** @Ajax @ValidParams @AllRequired */
	static function add(int $pageId){
		if(empty($pageId)) exit;
		$res=CmsMenu::add($pageId);
		if($res) VCmsMenu::generate();
		renderText($res ? '1' : '0');
	}
	/** @Ajax @ValidParams @AllRequired */
	static function del(int $pageId){
		$res=CmsMenu::deleteOneByPage_id($pageId);
		if($res) VCmsMenu::generate();
		renderText($res ? '1' : '0');
	}
	
	/** @Ajax @ValidParams @AllRequired */
	static function sort(array $pages){
		foreach($pages as $position=>$pageId)
			CmsMenu::QUpdateOneField('position',$position)->where(array('page_id'=>$pageId))->execute();
		VCmsMenu::generate();
		renderText('1');
	}
}