<?php
class SearchableController extends AController{
	/** @ValidParams @Id @NotEmpty('slug') */
	function view(int $id,$slug){
		$sbChild=ACSearchable::find();
		
		$rparams=CRoute::getParams();
		$controller=isset($rparams['subcontroller'])?$rparams['subcontroller']:get_class($sbChild)/*::$__pluralized*/;
		$action=isset($rparams['subaction'])?$rparams['subaction']:'view';
		unset($rparams['subcontroller'],$rparams['subaction']);
		
		self::callSubAction($controller,$action,$rparams,array($sbChild));
	}
}