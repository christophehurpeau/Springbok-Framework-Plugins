<?php
Controller::$defaultLayout='admin/cms';
/** @Check('ACSecureAdmin') @Acl('CMS') */
class PagesController extends Controller{
	/** */
	function index(){
		Page::Table()->fields('id,name,status,created,updated')
			->where(array('status !='=>Page::DELETED))->orderByCreated()
			->allowFilters()
			->paginate()->actionClick('edit')
			->render('Pages',true);
	}
	
	
	/** @ValidParams @Required('page')
	* page > @Valid('name')
	*/ function add(Page $page){
		$page->status=Page::DRAFT;
		$page->author_id=CSecure::connected();
		$page->visible=false;
		$page->insert();
		redirect('/pages/edit/'.$page->id);
	}
	
	/** @ValidParams @Required('id') */
	function edit(int $id){
		$page=Page::ById($id);
		notFoundIfFalse($page);
		mset($page,$id);
		render();
	}
	
	/** @ValidParams @Required('id') */
	function delete(int $id){
		Page::updateOneFieldByPk($id,'status',Page::DELETED);
		Page::onModified($id,true);
		redirect('/pages');
	}
	
	/** @ValidParams @AllRequired
	* page > @Valid('name','content') */
	function save(int $id,Page $page){
		$page->id=$id;
		if(empty($page->slug)) $page->slug=$page->auto_slug();
		$res=$page->save();
		PageHistory::create($page,PageHistory::SAVE);
		renderText($res);
	}


	/** @Ajax @ValidParams @Required('term') */
	function autocomplete($term){
		self::renderJSON(SModel::json_encode(
			Page::QAll()->fields('id,name,slug')
				->where(array('name LIKE'=>'%'.$term.'%','status !='=>Page::DELETED))
				->limit(14)
			,'_autocomplete'
		));
	}

	/** @Ajax @ValidParams @Required('val') */
	function checkId(int $val){
		$page=Page::ById($val)->fields('id,name,slug')->addCondition('status !=',Page::DELETED);
		self::renderJSON($page===false?'{"error":"Page inconnue"}':$page->toJSON_autocomplete());
	}
	
	/** @ValidParams */
	function tools(){
		render();
	}
	
	/** */
	function regenerateSitemap(){
		ACSitemapPages::generate();
		redirect('/pages/tools');
	}
}