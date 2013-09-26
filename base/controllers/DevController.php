<?php /*#if DEV */
Controller::$defaultLayout='Dev/default';
class DevController extends AController{
	
	/** */
	static function index(){
		render();
	}
	
	/** */
	static function query($query){
		if(!empty($query)){
			//ControllerFile::
		}
	}
}
/*#/if */