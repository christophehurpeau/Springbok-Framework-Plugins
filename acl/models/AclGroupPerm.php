<?php
/** @TableAlias('aclgp') */
class AclGroupPerm extends SSqlModel{
	public
		/** @Pk @SqlType('tinyint(3) unsigned') @NotNull
		* @ForeignKey('AclGroup','id')
		*/ $group_id,
		/** @Pk @SqlType('varchar(25)') @NotNull
		*/ $permission,
		/** @Boolean @Default(true)
		*/ $granted;
}