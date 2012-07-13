<?php
class VSeo extends SViewCachedElement{
	protected static $views=array('text','metas');
	
	/* DEV */ public function exists(){ return false; } /* /DEV */
	
	public static function path($type,$id){return DATA.'elementsCache/seo/'.$type.'_'.$id;}
	
	public static function vars($type,$id){
		$seo=$type::QOne()->where(array('id'=>$id));
		$seo->text=UHtml::transformInternalLinks($seo->text,Config::$internalLinks);
		return array('seo'=>$seo);
	}
	public function metas(){
		return json_decode($this->read('metas'),true);
	}
}