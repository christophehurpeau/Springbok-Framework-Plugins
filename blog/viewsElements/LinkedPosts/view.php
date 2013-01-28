{if!e $posts}
<h2>{t 'plugin.blog.postLinked_title'}</h2>
<ul class="nobullets cSepTop">
	{f $posts as $post}<? View::element('post',array('post'=>$post)) ?>{/f}
</ul>
{/if}
