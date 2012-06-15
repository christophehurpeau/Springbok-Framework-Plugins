<?php
/** @TableAlias('skt') @Created @Updated */
class SearchablesKeywordTerm extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		 * @ForeignKey('SearchablesKeyword','id')
		*/ $keyword_id,
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		 * @ForeignKey('SearchablesTerm','id')
		*/ $term_id;
}