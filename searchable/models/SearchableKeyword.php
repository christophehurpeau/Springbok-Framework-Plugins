<?php
/** @TableAlias('sk') */
class SearchableKeyword extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		*/ $searchable_id,
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		*/ $keyword_id;
}