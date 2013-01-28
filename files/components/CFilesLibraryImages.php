<?php
class ACFilesLibraryImages extends CImages{
	protected static $folderPrefix='library_';
	
	public static function folderPath(){
		return DATA.'library/';
	}
	
	protected static function createObject(){
		$image=new LibraryFile();
		$image->type=LibraryFile::IMAGE;
		return $image;
	}
	
}