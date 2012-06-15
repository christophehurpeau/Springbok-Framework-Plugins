<?php
class ACSitemapPosts{
	public static function generate(){
		$sitemap=new HSitemaps('posts_');
		$posts=Post::QAll()->fields('id,slug,published,updated')
			->where(array('status'=>Post::PUBLISHED));
		foreach($posts as $post)
			$sitemap->add($post->link(),array('priority'=>'0.8','changefreq'=>'yearly',
				'lastmod'=>date('c',strtotime($post->updated===null?$post->published:$post->updated))));
		$sitemap->end();
	}
}
