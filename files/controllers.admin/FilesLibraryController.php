<?php
/** @Check('ACSecureAdmin') @Acl('CMS') */
class FilesLibraryController extends Controller{
	/** @Ajax */
	function images(int $id){
		if($id===0) $id=false;
		
		CGallery::index($id,LibraryFile::IMAGE,'LibraryFolder','LibraryFile');
	}
	
	/** */
	function uploadImage(int $albumId){
		$image=new LibraryFile();
		$image->type=LibraryFile::IMAGE;
		if($albumId!==0) $image->album_id=$albumId;
		ACFilesLibraryImages::plupload($image);
	}
	
	/** @Ajax @ValidParams @AllRequired */
	function renameFile(int $id,$newName){
		LibraryFile::updateOneFieldByPk($id,'name',$newName);
		renderText('1');
	}
	
	/** @Ajax @ValidParams @AllRequired */
	function renameFolder(int $id,$newName){
		LibraryFolder::updateOneFieldByPk($id,'name',$newName);
		renderText('1');
	}
	
	/** @Ajax @ValidParams @Required('name') */
	function addFolder(int $parentId,$name){
		if($parentId===0) $parentId=null;
		$id=LibraryFolder::create($parentId,$name);
		renderJSON('{"id":'.$id.'}');
	}
}