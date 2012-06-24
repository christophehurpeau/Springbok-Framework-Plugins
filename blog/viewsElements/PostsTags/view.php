<div class="block1 mt10">
	<h4>{t 'plugin.blog.tagCloud'}</h4>
	<ul class="inline">
		{f $tags as $tag} <li style="font-size:{=$tag->size}pt">{link $tag->name,$tag->link()}</li>{/f}
	</ul>
</div>