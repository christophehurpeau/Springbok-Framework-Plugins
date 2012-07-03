<?php
/** @TableAlias('cmsi') @Created @Updated */
class CmsImage extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @Null
		*  @ForeignKey('CmsAlbum','id','onDelete'=>'CASCADE')
		*/ $album_id,
		/** @SqlType('varchar(128)') @NotNull
		*/ $name,
		/** @SqlType('float') @NotNull
		*/ $width,
		/** @SqlType('float') @NotNull
		*/ $height;
}