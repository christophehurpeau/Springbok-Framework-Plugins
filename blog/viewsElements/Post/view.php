<article itemscope itemtype="http://schema.org/Article">
	<h1 itemprop="name">{$post->title}</h1>
	{if!null $post->image->image_id}
		<?php $url=Config::$static_url.'/files/posts_images/'.$post->image->image_id; ?>
		{link '<img class="float_left mr10" itemprop="image" content="'.$url.'.jpg" width="75" height="75" src="'.$url.'-medium.jpg" />',$post->link(),array('escape'=>false)}
	{/if}
	
	/* IF(blog_displayExcerptInView) */<div class="ml20 italic" itemprop="description">{=$post->excerpt}</div>/* /IF */
	<div itemprop="articleBody" class="clearfix">
		{=$post->content}
	</div>
	
	/* IF(blog_personalizeAuthors_enabled) */
	<div class="alignRight">
		{t 'plugin.blog.Authors:'} <? implode(', ',array_map(function(&$author){return '<span itemprop="author">'.HHtml::link($author->name,$author->url).'</span>';},$post->authors)) ?>
	</div>
	/* /IF */
	
	<div class="block2 smallinfo">
		<span itemprop="dateCreated" content="{$post->created}"></span>
		{t 'plugin.blog.PublishedOn'} <span itemprop="datePublished" content="{=$post->published}"><? HTime::simple($post->published) ?></span>
	 	{if!null $post->updated}, {t 'plugin.blog.updatedOn'} <span itemprop="dateModified" content="{=$post->updated}"><? HTime::simple($post->updated) ?></span>{/if}</div>
	</div>
	
	
	{if!e $post->posts}
		<div class="sepTop">
			<h5>{t 'plugin.blog.postLinked_title'}</h5>
			<ul>
				{f $post->posts as $lPost}<li>{link $lPost->title,$lPost->link()}</li>{/f}
			</ul>
		</div>
	{/if}
</article>
