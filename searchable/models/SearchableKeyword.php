<?php
/** @TableAlias('sk') */
class SearchableKeyword extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Searchable','id')
		*/ $searchable_id,
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('SearchablesKeyword','id')
		*/ $keyword_id;
}