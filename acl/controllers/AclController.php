<?php
Controller::$defaultLayout='admin';
/** @Check('ACSecureAdmin') @Acl('Acl') */
class AclController extends Controller{
	const MODEL='AclGroup';
	
	use TreeController;
	
	
	/** @Acl('AclGroup') */
	static function index(){
		$modelName=self::MODEL;
		set('tree',$modelName::TreeView()->actionView('/acl/permissions'));
		render();
	}
	
	/*AclGroup::Table()->paginate()->actionClick('permissions')
		->render('Acl Groups',array('modelName'=>'AclGroup','form'=>array('action'=>'/acl/add')));*/
	
	/** @ValidParams @Acl('Acl') */
	static function permissions(int $groupId){
		if(empty($groupId)) $groupId=0;
		mset(array(
			'groupId'=>&$groupId,
			'groups'=>App::configArray('aclGroups'),
			'perms'=>AclGroupPerm::QList()->fields('permission,granted')->byGroup_id($groupId),
		));
		render();
	}
	
	/** @ValidParams @Required('perm') @Acl('Acl') */
	static function update(int $groupId,$perm,bool $value){
		if(empty($groupId)) $groupId=0;
		$gp=new AclGroupPerm;
		$gp->group_id=$groupId;
		$gp->permission=$perm;
		$gp->granted=$value;
		$gp->replace();
	}
}