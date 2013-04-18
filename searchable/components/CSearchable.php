<?php
class ACSearchable{
	public static function find($modelName=null,$options=array(),$redirect=true){
		$params=CRoute::getParams();
		if(empty($params['id']) || empty($params['slug'])) notFound();
		$id=(int)$params['id']; $slug=$params['slug'];
		
		if($modelName===null){
			$type=Searchable::QValue()->field('_type')->where(array('id'=>$id,'visible'=>true));
			if($type===false) notFound();
			$modelName=Config::$modelParents['type2model']['Searchable'][$type];
		}
		
		$sbChild=$modelName::findOneById($id,$options);
		notFoundIfFalse($sbChild);
		
		if($redirect===true && $sbChild->slug!==$slug) Controller::redirectPermanent($sbChild->link());
		Controller::setForLayoutAndView(lcfirst($modelName),$sbChild);
		
		return $sbChild;
	}
	
	public static function findBySlug($modelName=null,$options=array(),$redirect=true){
		$params=CRoute::getParams();
		if(empty($params['slug'])) notFound();
		$slug=$params['slug'];
		
		if($modelName===null){
			$row=Searchable::QRow()->fields('id,_type')->where(array('visible'=>true,'slug LIKE'=>$slug));
			if(!$row) $sbChild=false;
			else{
				$modelName=Config::$modelParents['type2model']['Searchable'][$row['_type']];
				$sbChild=$modelName::findOneById($row['id'],$options);
			}
		}else{
			$sbChild=$modelName::findOneBySlug($slug,$options);
		}
		
		
		if($redirect===true && $sbChild===false){
			//TODO try to redirect
			//redirectPermanent($sbChild->link());
		}
		
		notFoundIfFalse($sbChild);
		
		Controller::setForLayoutAndView(lcfirst($modelName),$sbChild);
		
		return $sbChild;
	}
}