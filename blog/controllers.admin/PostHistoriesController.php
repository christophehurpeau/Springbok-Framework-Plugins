<?php
Controller::$defaultLayout='admin/cms';
/** @Check('ACSecureAdmin') @Acl('Posts') */
class PostHistoriesController extends Controller{
	/** @Ajax @ValidParams @Required('id') */
	static function view(int $id){
		set('history',PostHistory::QAll()->fields('id,created')->byPost_id($id)->orderByCreated()->paginate());
		render();
	}
	
	/** @ValidParams @Required('id') */
	static function history(int $id){
		set('history',PostHistory::QOne()->byId($id)->mustFetch());
		render();
	}
	
	/** @Ajax @ValidParams @Required('id') */
	static function restore(int $id){
		$history=PostHistory::QOne()->byId($id)->mustFetch();
		$history->restore();
		Post::onModified($history->post_id);
		redirect('/posts/edit/'.$history->post_id);
	}
}