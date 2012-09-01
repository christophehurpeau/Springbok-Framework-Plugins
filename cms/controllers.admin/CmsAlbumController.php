<?php
/** @Check('ASecureAdmin') @Acl('CMS') */
class CmsAlbumController extends Controller{
	/** @Ajax */
	function index(int $id){
		if($id===0) $id=false;
		
		CGallery::index($id,'CmsAlbum','CmsImage');
	}
	
	/** */
	function upload(int $albumId){
		$image=new CmsImage();
		if($albumId!==0) $image->album_id=$albumId;
		ACCmsImages::plupload($image);
		/* DEV */
		if(!empty($image->id)){
			copy(dirname(APP).'/data/cms_images/'.$image->id.'.jpg',APP.'web/files/cms_images/'.$image->id.'.jpg');
			foreach (Config::$images['cms_thumbnails'] as $suffix=>$params)
				copy(dirname(APP).'/data/cms_images/'.$image->id.'-'.$suffix.'.jpg',APP.'web/files/cms_images/'.$image->id.'-'.$suffix.'.jpg');
		}
		/* /DEV */
	}
	
	/** @Ajax @ValidParams @AllRequired */
	function renameImage(int $id,$newName){
		CmsImage::updateOneFieldByPk($id,'name',$newName);
		renderText('1');
	}
	
	/** @Ajax @ValidParams @AllRequired */
	function renameAlbum(int $id,$newName){
		CmsAlbum::updateOneFieldByPk($id,'name',$newName);
		renderText('1');
	}
	
	/** @Ajax @ValidParams @Required('name') */
	function addAlbum(int $parentId,$name){
		if($parentId===0) $parentId=null;
		$id=CmsAlbum::create($parentId,$name);
		renderJSON('{"id":'.$id.'}');
	}
}