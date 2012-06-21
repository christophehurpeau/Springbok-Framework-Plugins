<?php
class ACPostSearch extends CSearch{
	
	protected static function createQuery(){
		return /**/Post::QListAll();
	}
}