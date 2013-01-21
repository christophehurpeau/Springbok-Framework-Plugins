<?php /* DEV */
Controller::$defaultLayout='Dev/default';
class DevController extends AController{
	
	/** */
	function index(){
		render();
	}
	
	/** */
	function query($query){
		if(!empty($query)){
			//ControllerFile::
		}
	}
}
/* /DEV */