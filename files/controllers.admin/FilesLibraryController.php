<?php
Controller::$defaultLayout='admin/cms';
/** @Check('ACSecureAdmin') @Acl('CMS') */
class FilesLibraryController extends Controller{
	/** */
	static function index(){
		LibraryFile::Table()->allowFilters()
			->paginate()
			->fields(array('id',
					'image'=>array('widthPx'=>1,'function'=>function($o){ return $o->type===LibraryFile::IMAGE ? $o->show('mini') : ''; },'escape'=>false),
					'name'=>array(
						'escape'=>false,
						'function'=>function($o){ return h($o->name).'<br/>'.HHtml::link(substr($link=$o->link(),0,1)==='/'?'\\'.$link:$link,false,array('target'=>'_blank')); }
					),
					'type'=>array('widthPx'=>1),
					'created','updated'
			))
			->actionDelete()
			->render(_t('plugins.files.FilesLibrary'),function(){
				echo $form=HForm::File()->action('/filesLibrary/upload')->attrClass('oneline');
				echo $form->inputFile('file')->noLabel();
				echo $form->select('type',LibraryFile::typesList())->noLabel();
				echo $form->end(_tC('Add'));
			});
	}
	/** */
	static function tools(){
		/* TODO : regenerate thumbnails,... */
		redirect('/filesLibrary');
	}
	
	/** */
	static function delete(int $id){
		if(CHttpRequest::referer(true) !== CRoute::getStringLink('admin','/filesLibrary')) exit;
		$file = LibraryFile::ById($id);
		if($file->type===LibraryFile::IMAGE) ACFilesLibraryImages::deleteFiles($id);
		else ACFilesLibrary::deleteFile($file);
		LibraryFile::deleteOneById($id);
		redirect('/filesLibrary');
	}
	
	/** */
	static function upload(int $type){
		if($type===LibraryFile::IMAGE) ACFilesLibraryImages::upload('file');
		else ACFilesLibrary::uploadAndDetect('file','ACFilesLibraryImages');
		redirect('/filesLibrary');
	}
	
	
	
	/** @Ajax */
	static function images(int $id){
		if($id===0) $id=false;
		
		CGallery::index($id,LibraryFile::IMAGE,'LibraryFolder','LibraryFile');
	}
	
	/** */
	static function uploadImage(int $albumId){
		$image=new LibraryFile();
		$image->type=LibraryFile::IMAGE;
		if($albumId!==0) $image->album_id=$albumId;
		ACFilesLibraryImages::plupload($image);
	}
	
	/** @Ajax @ValidParams @AllRequired */
	static function renameFile(int $id,$newName){
		LibraryFile::updateOneFieldByPk($id,'name',$newName);
		renderText('1');
	}
	
	/** @Ajax @ValidParams @AllRequired */
	static function renameFolder(int $id,$newName){
		LibraryFolder::updateOneFieldByPk($id,'name',$newName);
		renderText('1');
	}
	
	/** @Ajax @ValidParams @Required('name') */
	static function addFolder(int $parentId,$name){
		if($parentId===0) $parentId=null;
		$id=LibraryFolder::create($parentId,$name);
		renderJSON('{"id":'.$id.'}');
	}
}