<?php
class PageController extends AController{
	/** */
	function view($slug){
		if($slug===null) $pageId=1;
		else $pageId=Page::QValue()->field('id')->where(array('slug'=>$slug,'status'=>Page::PUBLISHED));
		if($pageId===false){
			$newSlug=PageSlugRedirect::get($slug);
			notFoundIfFalse($newSlug);
			redirect(array('/:slug',$newSlug));
		}
		
		$ve=VPage::create($pageId);
		set('metas',$ve->metas());
		$page=new Page;
		$page->id=$pageId;
		$page->slug=$slug;
		mset($ve,$page);
		self::_beforeRenderPage($pageId);
		render();
	}
	
	/** @SubAction('Searchable') */
	function viewSearchable($page){
		$ve=VPage::create($page->id);
		set('metas',$ve->metas());
		mset($ve);
		render('view');
	}
	
	public static function _beforeRenderPage($pageId){}
	
}