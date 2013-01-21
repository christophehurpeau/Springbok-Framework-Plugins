<?php
class SearchableReindexAllCli{
	public static function main(){
		Searchable::QRows()->fields('id,name,visible')->callback(function($sb){
			if($sb['visible']===false || $sb['visible']===null) SearchableWord::deleteFor($sb['id']);
			else SearchableWord::add($sb['id'],$sb['name']);
		});
	}
}
