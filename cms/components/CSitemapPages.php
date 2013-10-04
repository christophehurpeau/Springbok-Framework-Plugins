<?php
class ACSitemapPages{
	public static function generate(){
		$sitemap=new HSitemaps('pages_');
		
		$pages=Page::QAll()->fields('id,name,slug,published,updated')
			->where(array('status'=>Page::PUBLISHED))
			->fetch();
		foreach($pages as $page)
			$sitemap->add($page->link(),array('priority'=>'0.8','changefreq'=>'yearly',
				'lastmod'=>date('c',strtotime($page->updated===null?$page->published:$page->updated))));
		$sitemap->end();
	}
}
