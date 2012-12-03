<?php
class UpdateSearchablePagesCli{
	public static function main(){
		set_time_limit(0); ini_set('memory_limit', '1024M');
		
		Searchable::QAll()->where(array('visible'=>true,'_type'=>5))->callback(function($searchable){
			$searchable->update();
		});
	}
}