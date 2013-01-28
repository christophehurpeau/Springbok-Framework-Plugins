<?php
Controller::$defaultLayout='admin/cms';
/** @Check('ACSecureAdmin') @Acl('CMS') */
class FilesLibraryController extends Controller{
	/** */
	function index(){
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
			->render(_t('plugins.files.FilesLibrary'),function(){
				echo $form=HForm::File()->action('/filesLibrary/upload')->attrClass('oneline');
				echo $form->inputFile('file')->noLabel();
				echo $form->select('type',LibraryFile::typesList())->noLabel();
				echo $form->end(_tC('Add'));
			});
	}
	/** */
	function tools(){
		/* TODO : regenerate thumbnails,... */
		redirect('/filesLibrary');
	}
	
	/** */
	function upload(int $type){
		if($type===LibraryFile::IMAGE) ACFilesLibraryImages::upload('file');
		else ACFilesLibrary::uploadAndDetect('file','ACFilesLibraryImages');
		redirect('/filesLibrary');
	}
	
	
	
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