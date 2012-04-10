<?php
/** @TableAlias('i') */
class PostsImage extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @Null
		*  @ForeignKey('PostsAlbum','id','onDelete'=>'CASCADE')
		*/ $album_id,
		/** @SqlType('varchar(128)') @NotNull
		*/ $name,
		/** @SqlType('float') @NotNull
		*/ $width,
		/** @SqlType('float') @NotNull
		*/ $height,
		/** @SqlType('datetime') @NotNull
		*/ $created,
		/** @SqlType('datetime') @Null @Default(NULL)
		*/ $updated;
}