<?php
class SearchablesTermRenormalizeAllCli{
	public static function main(){
		SearchablesTerm::QAll()->fields('id,term,slug')->callback('_renormalize()');
	}
}