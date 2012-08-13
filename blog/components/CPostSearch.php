<?php
class ACPostSearch extends CSearch{
	
	public static function createQuery(){
		return /**/Post::QListAll();
	}
}