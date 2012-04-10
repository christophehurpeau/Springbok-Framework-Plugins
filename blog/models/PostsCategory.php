<?php
/** @TableAlias('c') */
class PostsCategory extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('VARCHAR(60)') @NotNull
		* @Unique
		*/ $name,
		/** @Boolean @Default(true)
		*/ $home_page,
		/** @SqlType('datetime') @NotNull
		*/ $created,
		/** @SqlType('datetime') @Null @Default(NULL)
		*/ $updated;
}