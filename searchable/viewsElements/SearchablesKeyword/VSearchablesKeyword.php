<?php
class VSearchablesKeyword extends SViewCachedElement{
	protected static $views=array('descr','metas');
	
	/* DEV */ public function exists(){ return false; } /* /DEV */
	
	public static function path($id){return DATA.'elementsCache/searchablesKeywords/'.$id;}
	
	public static function vars($id){
		$sk=SearchablesKeyword::QOne()->where(array('id'=>$id));
		$sk->descr=UHtml::transformInternalLinks($sk->descr,Config::$internalLinks);
		return array('sk'=>$sk);
	}
	public function metas(){
		return json_decode($this->read('metas'),true);
	}
}