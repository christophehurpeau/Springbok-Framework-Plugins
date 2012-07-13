<?php
class ACSitemapPosts{
	public static function generate(){
		$sitemap=new HSitemaps('posts_');
		
		$tags=PostsTag::QValues()->setFields(false)->with('MainTerm','slug');
		foreach($tags as $tag) $sitemap->add('/posts/tag/'.$tag,array('priority'=>'0.4'));
		
		
		$posts=Post::QAll()->fields('id,published')->withParent('name,slug,updated')
			->where(array('status'=>Post::PUBLISHED));
		foreach($posts as $post)
			$sitemap->add($post->link(),array('priority'=>'0.8','changefreq'=>'yearly',
				'lastmod'=>date('c',strtotime($post->updated===null?$post->published:$post->updated))));
		$sitemap->end();
	}
}
