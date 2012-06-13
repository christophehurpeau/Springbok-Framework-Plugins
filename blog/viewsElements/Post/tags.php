<div class="block1">
	<h4>{t 'plugin.blog.Tags:'}</h4>
	<ul class="inline">
		{f $post->tags as $tag}<li>{link $tag->name,$tag->link()}</li>{/f}
	</ul>
</div>