<?php
class ACFilesLibrary extends CFiles{
	protected static $folderPrefix='library_';
	
	public static function folderPath(){
		return DATA.'library/';
	}
	
	protected static function createObject(){
		$file=new LibraryFile();
		$file->type=LibraryFile::FILE;
		return $file;
	}
	
}