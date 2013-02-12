<?php
/** @TableAlias('sta') */
class SearchablesTermAbbreviation extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('SearchablesTerm','id')
		*/ $term_id,
		/** @Pk @Unique @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('SearchablesTerm','id')
		*/ $abbr_id;
}