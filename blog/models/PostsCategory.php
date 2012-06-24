<?php
/** @TableAlias('c') @Child('SearchablesKeyword') */
class PostsCategory extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @Boolean @Default(true)
		*/ $home_page;
}