<?php
/** @TableAlias('sc') @TableName('site_connected') */
class SiteConnected extends SSqlModel{
	public
		/** @Pk @SqlType('varchar(10)') @NotNull
		*/ $key,
		/** @SqlType('tinytext') @NotNull
		*/ $access_token,
		/** @SqlType('tinytext') @Null
		*/ $refresh_token,
		/** @SqlType('datetime') @NotNull
		*/ $created,
		/** @SqlType('datetime') @Null
		*/ $updated;
}