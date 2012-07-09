{=$pagination=HPagination::simple($posts)}

<ul class="nobullets cMt10">
{f $posts->getResults() as $post}
	<li itemscope itemtype="http://schema.org/Article" class="block1 clearfix">
		{if!null $post->image->image_id}
			<?php $url=Config::$static_url.'/files/cms_images/'.$post->image->image_id; ?>
			{link '<img class="floatL mr10" itemprop="image" content="'.$url.'.jpg" width="75" height="75" src="'.$url.'-medium.jpg" />',$post->link(),array('escape'=>false)}
		{/if}
		<h3 class="noclear" itemprop="name">{link $post->name,$post->link(),array('itemprop'=>'url')}</h3>
		<? VPost::create($post->id)->render('excerpt') ?>
		<div class="alignRight">{link _t('plugin.blog.readMore'),$post->link()}</div>
		<div>{if!e $post->tags}{t 'plugin.blog.Tags:'} <? implode(', ',array_map(function(&$tag){return HHtml::link($tag->name,$tag->link());},$post->tags)) ?><br />{/if}
		{link _t('plugin.blog.permalink'),$post->link()}/* IF(blog_comments_enabled) */{if $post->isCommentsAllowed()} | {if $post->comments===0}{t 'plugin.blog.NoComments'}{else}<? HHTml::link(_t_p('Comments',$post->comments),$post->link+array('#'=>'comments')) ?> ({$post->comments}){/if}{/if}/* /IF */
		 | <span itemprop="dateCreated" content="{$post->created}"></span>
			{t 'plugin.blog.PublishedOn'} <span itemprop="datePublished" content="{=$post->published}"><? HTime::simple($post->published) ?></span>
			{if!null $post->updated}, {t 'plugin.blog.updatedOn'} <span itemprop="dateModified" content="{=$post->updated}"><? HTime::simple($post->updated) ?></span>{/if}
		</div>
	</li>
{/f}
</ul>

{=$pagination}
