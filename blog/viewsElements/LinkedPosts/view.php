{if!e $posts}
	<h2><?= _t($latest ? 'plugin.blog.PostsLatestTitle' : 'plugin.blog.postLinked_title') ?></h2>
	<ul class="nobullets cSepTop">
		{f $posts as $post}<? View::element('post',array('post'=>$post)) ?>{/f}
	</ul>
{/if}