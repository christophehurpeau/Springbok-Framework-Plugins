<?php /* DEV */
Controller::$defaultLayout='Dev/db';
class DevDbController extends AController{
	
	/** */
	function beforeRender(){
		$models=array();
		foreach(new FilesystemIterator(APP.'models/infos') as $file){
			$modelName=$file->getFilename();
			$models[$modelName::$__dbName][]=$modelName;
		}
		ksort($models);
		self::setForLayout('models',$models);
		return true;
	}
	
	/** */
	function index(){
		render();
	}
	
	/** */
	function model($modelName){
		self::beforeRender();
		$modelName::Table()->noAutoRelations()
			->paginate()
			->render($modelName);
	}
}
/* /DEV */