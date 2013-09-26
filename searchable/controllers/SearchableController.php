<?php
class SearchableController extends AController{
	/** @ValidParams @Id @NotEmpty('slug') */
	static function view(int $id,$slug){
		$sbChild=ACSearchable::find();
		self::_callSubAction($sbChild);
	}
	
	/**  */
	static function viewBySlug(){
		$sbChild=ACSearchable::findBySlug();
		self::_callSubAction($sbChild);
	}
	
	private static function _callSubAction($sbChild){
		$rparams=CRoute::getParams();
		$controller=isset($rparams['subcontroller'])?$rparams['subcontroller']:get_class($sbChild)/*::$__pluralized*/;
		$action=isset($rparams['subaction'])?$rparams['subaction']:'view';
		unset($rparams['subcontroller'],$rparams['subaction']);
		
		self::callSubAction($controller,$action,$rparams,array($sbChild));
	}
}