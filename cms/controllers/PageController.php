<?php
class PageController extends AController{
	/** @ValidParams('/') @NotEmpty('slug') */
	function view($slug){
		$pageId=Page::QValue()->field('id')->where(array('slug'=>$slug,'status'=>Page::PUBLISHED));
		notFoundIfFalse($pageId);
		
		$ve=VPage::create($pageId);
		set('metas',$ve->metas());
		$page=new Page;
		$page->id=$pageId;
		$page->slug=$slug;
		mset($ve,$page);
		self::_beforeRenderPage($pageId);
		render();
	}
	
	public static function _beforeRenderPage($pageId){}
	
}