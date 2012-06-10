<?php
/* IF(blog_personalizeAuthors_enabled) */
/** @TableAlias('a') */
class PostsAuthor extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('varchar(60)') @NotNull
		* @Unique
		*/ $name,
		/** @SqlType('varchar(60)') @NotNull
		* @Unique
		*/ $url,
		/** @SqlType('datetime') @NotNull
		*/ $created,
		/** @SqlType('datetime') @Null @Default(NULL)
		*/ $updated;
}
/* /IF */