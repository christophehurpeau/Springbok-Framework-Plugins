<?php
Controller::$defaultLayout='admin/users';
/** @Check('ACSecureAdmin') @Acl('Users') */
class UsersController extends Controller{
	/** */
	function index(){
		User::Table()->fields('id,email,pseudo,first_name,last_name,gender/* IF(users.pseudo) */,pseudo/* /IF */,type,status,created,updated')
			->allowFilters()->paginate()->actionClick('view')->render('Utilisateurs');
	}
	
	/** @ValidParams @Required('id') */
	function view(int $id){
		$user=User::ById($id);
		notFoundIfFalse($user);
		mset($user);
		$paginate=$user->findWithPaginate('UserHistory',array('with'=>array('type'),'orderBy'=>array('created'=>'DESC')));
		$paginate->pageSize(25)->execute();
		render();
	}
	
	
	/** */
	function connections(){
		UserConnection::Table()->orderByCreated()
			->allowFilters()
			->paginate()->fields(array('created','type','succeed','login',
					'connected'=>array('align'=>'center','escape'=>false,
							'callback'=>function($v){return empty($v)?h($v):HHtml::link($v,'/users/view/'.$v);}),
					'ip'))
			->render('Connexions');
	}
}