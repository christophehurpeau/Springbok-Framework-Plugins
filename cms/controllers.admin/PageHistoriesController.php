<?php
Controller::$defaultLayout='admin/cms';
/** @Check('ACSecureAdmin') @Acl('CMS') */
class PageHistoriesController extends Controller{
	/** @Ajax @ValidParams @Required('id') */
	function view(int $id){
		set('history',PageHistory::QAll()->fields('id,created')->byPage_id($id)->orderByCreated()->paginate());
		render();
	}
	
	/** @ValidParams @Required('id') */
	function history(int $id){
		$history=PageHistory::QOne()->byId($id);
		notFoundIfFalse($history);
		mset($history);
		render();
	}
	
	/** @Ajax @ValidParams @Required('id') */
	function restore(int $id){
		$history=PageHistory::QOne()->byId($id);
		notFoundIfFalse($history);
		$history->restore();
		Page::onModified($history->page_id);
		redirect('/pages/edit/'.$history->page_id);
	}
}