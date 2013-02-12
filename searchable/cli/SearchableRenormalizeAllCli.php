<?php
class SearchableRenormalizeAllCli{
	public static function main(){
		Searchable::beginTransaction();
		Searchable::QAll()->fields('id,name')->callback(function($sb){
			$sb->updated=false;
			$sb->normalized=$sb->normalized();
			$sb->html_name=$sb->htmlName();
			unset($sb->name);
			$sb->update();
		});
		Searchable::commit();
	}
}