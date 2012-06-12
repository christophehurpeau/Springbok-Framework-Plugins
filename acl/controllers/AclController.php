<?php
Controller::$defaultLayout='admin';
/** @Check @Acl */
class AclController extends Controller{
	/** @Acl('AclGroup') */
	function index(){
		AclGroup::Table()->paginate()->actionClick('permissions')
			->render('Acl Groups',array('modelName'=>'AclGroup','form'=>array('action'=>'/acl/add')));
	}
	
	/** @ValidParams @Acl('Acl') */
	function permissions(int $groupId){
		if(empty($groupId)) $groupId=0;
		mset(array(
			'groupId'=>&$groupId,
			'groups'=>App::configArray('aclGroups'),
			'perms'=>AclGroupPerm::QList()->fields('permission,granted')->byGroup_id($groupId),
		));
		render();
	}
	/** @ValidParams @Required('aclGroup') @Acl('AclGroup') */
	function add(AclGroup $aclGroup){
		$aclGroup->insert();
		redirect('/acl');
	}
	
	/** @ValidParams @Required('perm') @Acl('Acl') */
	function update(int $groupId,$perm,bool $value){
		if(empty($groupId)) $groupId=0;
		$gp=new AclGroupPerm;
		$gp->group_id=$groupId;
		$gp->permission=$perm;
		$gp->granted=$value;
		$gp->replace();
	}
}