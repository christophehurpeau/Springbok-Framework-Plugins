{=$pagination=HPagination::simple($posts)}

<ul class="nobullets cMt10">
{f $posts->getResults() as $post}
	<li itemscope itemtype="http://schema.org/Article" class="block1 clearfix">
		{if!null $post->image->image_id}
			<?php $url=Config::$static_url.'/files/posts_images/'.$post->image->image_id; ?>
			{link '<img class="float_left mr10" itemprop="image" content="'.$url.'.jpg" width="75" height="75" src="'.$url.'-medium.jpg" />',$post->link(),array('escape'=>false)}
		{/if}
		<h3 class="noclear" itemprop="name">{link $post->title,$post->link(),array('itemprop'=>'url')}</h3>
		{=$post->excerpt}
		<div class="alignRight">{link _t('plugin.blog.readMore'),$post->link()}</div>
		<div>{if!e $post->tags}{t 'plugin.blog.Tags:'} <? implode(', ',array_map(function(&$tag){return HHtml::link($tag->name,$tag->link());},$post->tags)) ?><br />{/if}
		{link _t('plugin.blog.permalink'),$post->link()}/* IF(blog_comments_enabled) */{if $post->isCommentsAllowed()} | {if $post->comments===0}{t 'No comments'}{else}<? HHTml::link(_t_p('Comments',$post->comments),$post->link+array('#'=>'comments')) ?> ({$post->comments}){/if}{/if}/* /IF */
		 | <span itemprop="dateCreated" content="{$post->created}"></span>
		 	{t 'plugin.blog.PublishedOn'} <span itemprop="datePublished" content="{=$post->published}"><? HTime::simple($post->published) ?></span>
		 	{if!null $post->updated}, {t 'plugin.blog.updatedOn'} <span itemprop="dateModified" content="{=$post->updated}"><? HTime::simple($post->updated) ?></span>{/if}</div>
	</li>
{/f}
</ul>

{=$pagination}
