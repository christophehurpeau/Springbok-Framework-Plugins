<?php
class SearchableRenormalizeAllCli{
	public static function main(){
		Searchable::QAll()->fields('id,name')->callback('_renormalize()');
	}
}