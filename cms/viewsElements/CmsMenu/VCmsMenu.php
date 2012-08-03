<?php
class VCmsMenu extends SViewCachedElement{
	
	/* DEV */ public function exists(){ return false; } /* /DEV */
	
	public static function path(){return DATA.'elementsCache/cmsMenu';}
	
	public static function vars(){
		$pages=Page::QAll()->fields('id,name,slug')
			->with('CmsMenu',array('fields'=>false,'type'=>QSelect::INNER))
			->addCondition('status',Page::PUBLISHED)
			->orderBy(array('cm.position'));
		$links=array();
		foreach($pages as &$page)
			$links[$page->name]=array($page->link());
		
		return array('links'=>$links);
	}
}