<?php
Controller::$defaultLayout='Dev/tests';
class DevTestsController extends Controller{
	/** */
	function beforeRender(){
		self::setForLayout('tests',new RecursiveDirectoryIterator(APP.'tests',FilesystemIterator::SKIP_DOTS));
		return true;
	}
	
	/** */
	function index(){
		render();
	}
	
	/** */
	function view($file){
		$results=include APP.'tests/'.str_replace('..','',$file);
		if(empty($results) || $results===1){
			$className=basename($file,'.php').'Test';
			if(class_exists($className,false)){
				$results=$className::run();
			}
		}
		mset($results);
		render();
	}
}
