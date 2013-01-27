<?php
/** @TableAlias('lf') @Created @Updated */
class LibraryFile extends SSqlModel{
	const FILE=0,IMAGE=1;
	
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @Null
		*  @ForeignKey('LibraryFolder','id','onDelete'=>'CASCADE')
		*/ $folder_id,
		/** @SqlType('varchar(128)') @NotNull
		*/ $name,
		/** @SqlType('varchar(5)') @NotNull
		*/ $ext,
		/** @SqlType('tinyint(1) unsigned') @NotNull
		* @Enum('File','Image')
		*/ $type,
		/** @SqlType('float') @Null
		*/ $width,
		/** @SqlType('float') @Null
		*/ $height;
	
	public function show($size='small'){
		return self::display($this->id,$this->name,$size);
	}
	public function link(){
		return self::linkFile($this->id,$this->ext);
	}
	
	public static function display($id,$alt='photo',$size='small'){
		$thumb=Config::$images['library_thumbnails'][$size];
		return '<img width="'.$thumb['width'].'" height="'.$thumb['height'].'" alt="'.h($alt).'"'
			.' src="'.self::linkImage($id,'-'.$size).'"/>';
	}
	public static function linkImage($id,$suffix='') {
		return HHtml::staticUrl('/files/library/'.$id.$suffix.'.jpg');
	}
	
	public static function linkFile($id,$ext){
		return HHtml::staticUrl('/files/library/'.$id.'.'.$ext);
	}
}