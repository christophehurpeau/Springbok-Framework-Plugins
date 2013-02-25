<?php
class SearchablesTermRenormalizeAllCli{
	public static function main(){
		SearchablesTerm::QAll()->fields('id,term')->callback('_renormalize()');
	}
}