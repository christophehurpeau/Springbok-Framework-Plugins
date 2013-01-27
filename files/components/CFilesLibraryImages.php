<?php
class ACFilesLibraryImages extends CImages{
	protected static $folderPrefix='library_';
	
	public static function folderPath(){
		return DATA.'library/';
	}
}