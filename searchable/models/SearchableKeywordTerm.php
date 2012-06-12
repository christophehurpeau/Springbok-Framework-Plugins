<?php
/** @TableAlias('skt') @Created @Updated */
class SearchableKeywordTerm extends SSqlModel{
	public
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		 * @ForeignKey('SearchableKeyword','id')
		*/ $keyword_id,
		/** @Pk @SqlType('int(10) unsigned') @NotNull
		 * @ForeignKey('SearchableTerm','id')
		*/ $term_id;
}