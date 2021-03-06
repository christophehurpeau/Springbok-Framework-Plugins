<?php
Controller::$defaultLayout='Dev/tests';
class DevTestsController extends Controller{
	/** */
	static function beforeRender(){
		self::setForLayout('tests',STest::directoryIterator());
		return true;
	}
	
	/** */
	static function index(){
		render();
	}
	
	/** */
	static function all(){
		set_time_limit(0);
		$tests=STest::directoryIterator(); $allResults=array();
		UPhp::recursive(function($callback,$tests) use(&$allResults){
			foreach($tests as $path=>$file){
				if($file->isDir())
					$callback($callback,new RecursiveDirectoryIterator($path,FilesystemIterator::SKIP_DOTS));
				else
					$allResults[$path]=STest::runFile($path);
			}
		},$tests);
		set('allResults',$allResults);
		set('tests',$tests);
		render();
	}
	
	/** */
	static function view($file){
		set('results',STest::runFile(APP.'tests/'.str_replace('..','',$file)));
		render();
	}
}
