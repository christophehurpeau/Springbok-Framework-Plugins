<?php
Controller::$defaultLayout='admin/cms';
/** @Check('ACSecureAdmin') @Acl('CMS') */
class ConnectedServicesController extends Controller{
	/** */
	static function index(){
		render();
	}
	
	/** @ValidParams @NotEmpty('key') */
	static function connectTo($key){
		if($key==='facebook')
			COAuth2Facebook::redirectForConnection(HHtml::url('/connectedServices/facebookResponse','admin',true),'','manage_pages,publish_stream');
		//elseif($key==='gplus')
		//	COAuth2Google::redirectForConnection(HHtml::url('/connectedServices/facebookResponse','admin',true),'',)
	}
}