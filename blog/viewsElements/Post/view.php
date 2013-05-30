<article itemscope itemtype="http://schema.org/Article">
	<h1 itemprop="name">{$post->name}</h1>
	{if!null $post->image->image_id}
		<?php $url=STATIC_URL.'/files/library/'.$post->image->image_id; ?>
		{link '<img class="floatL mr10" itemprop="image" content="'.$url.'.jpg" width="75" height="75" src="'.$url.'-medium.jpg" />',$post->link(),array('escape'=>false)}
	{/if}
	
	/*#if blog_displayExcerptInView*/<div class="ml20 italic" itemprop="description">{=$post->excerpt}</div>/*#/if*/
	<div itemprop="articleBody" class="clearfix">
		{=$post->content}
	</div>
	
	/*#if blog_personalizeAuthors_enabled*/
	<div class="alignRight">
		© {t 'plugin.blog.Authors:'} <? implode(', ',array_map(function(&$author){return '<span itemprop="author">'.HHtml::link($author->name,$author->url,array('rel'=>'author')).'</span>';},$post->authors)) ?>
	</div>
	/*#/if*/
	
	<div class="block2 smallinfo">
		<span itemprop="dateCreated" content="{$post->created}"></span>
		{t 'plugin.blog.PublishedOn'} <time pubdate datetime="{=$post->published}" itemprop="datePublished" content="{=$post->published}"><? HTime::simple($post->published) ?></time>
	 	{if!null $post->updated}, {t 'plugin.blog.updatedOn'} <time datetime="{=$post->updated}" itemprop="dateModified" content="{=$post->updated}"><? HTime::simple($post->updated) ?></span>{/if}
	</div>
	
	
	{if!e $post->posts}
		<div class="sepTop">
			<h5>{t 'plugin.blog.postLinked_title'}</h5>
			<ul class="compact">
				{f $post->posts as $lPost}<li>{link $lPost->name,$lPost->link()}</li>{/f}
			</ul>
		</div>
	{/if}
</article>
