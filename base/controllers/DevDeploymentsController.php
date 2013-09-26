<?php
define('SRC',dirname(APP).'/src/');
Controller::$defaultLayout='Dev/deployments';
class DevDeploymentsController extends Controller{
	private static $deployments;
	/** */
	static function beforeRender(){
		self::setForLayout('deployments',self::$deployments=UFile::getYAML(SRC.'config/deployments.yml'));
		return true;
	}
	
	/** */
	static function index(){
		render();
	}
}