<?php
/** @Check('ASecureAdmin') @Acl('Posts') */
class PostsAlbumController extends Controller{
	/** @Ajax */
	function index(int $id){
		if($id===0) $id=false;
		
		CGallery::index($id,'PostsAlbum','PostsImage');
	}
	
	/** */
	function upload(int $albumId){
		$image=new PostsImage();
		if($albumId!==0) $image->album_id=$albumId;
		CImages::plupload($image,true,'posts_');
		/* DEV */
		if(!empty($image->id)){
			copy(dirname(APP).'/data/posts_images/'.$image->id.'.jpg',APP.'web/files/posts_images/'.$image->id.'.jpg');
			foreach (Config::$images['posts_thumbnails'] as $suffix=>$params)
				copy(dirname(APP).'/data/posts_images/'.$image->id.'-'.$suffix.'.jpg',APP.'web/files/posts_images/'.$image->id.'-'.$suffix.'.jpg');
		}
		/* /DEV */
	}
	
	/** @Ajax @ValidParams @AllRequired */
	function renameImage(int $id,$newName){
		PostsImage::updateOneFieldByPk($id,'name',$newName);
		renderText('1');
	}
	
	/** @Ajax @ValidParams @AllRequired */
	function renameAlbum(int $id,$newName){
		PostsAlbum::updateOneFieldByPk($id,'name',$newName);
		renderText('1');
	}
	
	/** @Ajax @ValidParams @Required('name') */
	function addAlbum(int $parentId,$name){
		if($parentId===0) $parentId=null;
		$id=PostsAlbum::create($parentId,$name);
		renderJSON('{"id":'.$id.'}');
	}
}