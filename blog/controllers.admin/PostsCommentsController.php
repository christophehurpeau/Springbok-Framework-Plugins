<?php
Controller::$defaultLayout='admin/blog';
/** @Check('ASecureAdmin') @Acl('Posts') */
class PostsCommentsController extends Controller{
	/** */
	function index(){
		HBreadcrumbs::set(array( 'Articles'=>'/posts', ));
		PostComment::Table()
			->fields('id,post_id,comment,status,created')
			->with('User',array('fields'=>'pseudo','fieldsInModel'=>true))->orderByCreated()
			->allowFilters()
			->paginate()
			->render('Commentaires');
	}
	
	/** */
	function validation(){
		HBreadcrumbs::set(array(
			'Articles'=>'/posts',
			'Commentaires'=>'/postsComments',
		));
		PostComment::Table()->byStatus(PostComment::WAITING_VALIDATION)->orderByCreated()
			->fields('id,post_id,comment,created')
			->with('User',array('fields'=>'pseudo','fieldsInModel'=>true))
			->allowFilters()
			->paginate()
			->actions(array('tick','validate'),array('cross','deny'))
			->render('Validation des commentaires');
	}
	
	/** */
	function validate(int $id){
		PostComment::QUpdateOneField('status',PostComment::VALID)->where(array('id'=>$id,'status'=>PostComment::WAITING_VALIDATION))->limit1();
		redirect('/postsComments/validation');
	}
	
	/** */
	function deny(int $id){
		PostComment::QUpdateOneField('status',PostComment::DENIED)->where(array('id'=>$id,'status'=>PostComment::WAITING_VALIDATION))->limit1();
		redirect('/postsComments/validation');
	}
}