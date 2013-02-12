<?php
class SearchableRenormalizeAllCli{
	public static function main(){
		Searchable::beginTransaction();
		Searchable::QAll()->fields('id,name')->callback('_renormalize()');
		Searchable::commit();
	}
}