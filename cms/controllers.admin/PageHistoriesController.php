<?php
Controller::$defaultLayout='admin/cms';
/** @Check('ACSecureAdmin') @Acl('CMS') */
class PageHistoriesController extends Controller{
	/** @Ajax @ValidParams @Required('id') */
	static function view(int $id){
		set('history',PageHistory::QAll()->fields('id,created')->byPage_id($id)->orderByCreated()->paginate());
		render();
	}
	
	/** @ValidParams @Required('id') */
	static function history(int $id){
		set('history',PageHistory::QOne()->byId($id)->notFoundIfFalse());
		render();
	}
	
	/** @Ajax @ValidParams @Required('id') */
	static function restore(int $id){
		$history=PageHistory::QOne()->byId($id)->notFoundIfFalse();
		$history->restore();
		Page::onModified($history->page_id);
		redirect('/pages/edit/'.$history->page_id);
	}
}