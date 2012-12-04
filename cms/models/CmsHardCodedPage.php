<?php
/** @TableAlias('hcp') @Child('Searchable','created,updated') */
class CmsHardCodedPage extends Searchable{
	const VALID=2,ARCHIVED=3,DELETED=4;
	public
		/** @SqlType('varchar(255)') @NotNull
		*/ $link,
		/** @SqlType('tinyint(1)') @NotNull
		* @Enum(2=>'Published',3=>'Archived',4=>'Deleted')
		*/ $status;
}