<?php
class VPage extends SViewCachedElement{
	protected static $views=array('view','metas');
	
	/* DEV */ public function exists(){ return false; } /* /DEV */
	
	public static function path($id){return DATA.'elementsCache/pages/'.$id;}
	
	public static function vars($id){
		$page=Page::QOne()->where(array('id'=>$id));
		notFoundIfFalse($page);
		$page->content=UHtml::transformInternalLinks($page->content,Config::$internalLinks,'index',false);
		
		return array('page'=>$page);
	}
	public function metas(){
		return json_decode($this->read('metas'),true);
	}
}