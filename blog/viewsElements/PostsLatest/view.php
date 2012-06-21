<h2>{t 'plugin.blog.PostsLatestTitle'}</h2>
<ul class="nobullets cSepTop">
	{f $posts as $post}<? View::element('post',array('post'=>&$post)) ?>{/f}
</ul>