<?php
class SitemapJob{
	public static function main(){
		ACSitemap::generate();
	}
}