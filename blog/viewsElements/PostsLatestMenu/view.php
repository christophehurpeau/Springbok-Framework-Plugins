<div class="block1 mt10">
	<h4>{t 'plugin.blog.LatestPosts'}</h2>
	<ul class="nobullets overflow-ellipsis smallinfo">
	{f $posts as $post}<li itemscope itemtype="http://schema.org/Article"><span itemprop="name">{link $post->title,$post->link(),array('title'=>$post->title,'itemprop'=>'url')}</span></li>{/f}
	</ul>
</div>