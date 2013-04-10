<?php
class ACSearchable{
	public static function find($modelName=null,$redirect=true){
		$params=CRoute::getParams();
		if(empty($params['id']) || empty($params['slug'])) notFound();
		$id=(int)$params['id']; $slug=$params['slug'];
		
		if($modelName===null){
			$type=Searchable::QValue()->field('_type')->byId($id);
			if($type===false) notFound();
			$modelName=Config::$modelParents['type2model']['Searchable'][$sbType];
		}
		
		$sbChild=$modelName::findOneById($id);
		notFoundIfFalse($sbChild);
		
		if($redirect===true && $sbChild->slug!==$slug) redirectPermanent($sbChild->link());
		Controller::setForLayoutAndView(lcfirst($modelName),$sbChild);
		
		return $sbChild;
	}
}