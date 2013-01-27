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
}