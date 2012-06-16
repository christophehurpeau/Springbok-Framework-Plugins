<?php
class SearchableWordsCli{
	public static function doCli(){
		$db=SearchableWord::$__modelDb;
		$db->doUpdate('SET FOREIGN_KEY_CHECKS=0');
		SearchableWord::truncate(); SearchablesWord::truncate(); SearchableTermWord::truncate();
		$db->doUpdate('SET FOREIGN_KEY_CHECKS=1');
		set_time_limit(0); ini_set('memory_limit', '1024M');
				
		foreach(Searchable::QRows()->fields('id,name')->byVisible(true) as $searchable){
			SearchableWord::add((int)$searchable['id'],$searchable['name']);
		}
		foreach(SearchablesTerm::QRows()->fields('id,term') as $term){
			SearchableTermWord::add((int)$term['id'],$term['term']);
		}
	}
}
SearchableWordsCli::doCli();