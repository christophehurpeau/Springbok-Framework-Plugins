<?php new View($post->meta_title); HMeta::canonical($post->link());
HBreadcrumbs::set(array('/* VALUE(blog_title) */'=>'/post'))
?>

	<div class="block1 mt10">
		<h2>Mots clés de l'article</h2>
		<div class="content clearfix">
			<ul class="mosaic">
				{f $post->tags as $tag}<li>{link $tag->name,$tag->link()}</li>{/f}
			</ul>
		</div>
	</div>
<div itemscope itemtype="http://schema.org/Article">
	<h1 itemprop="name">{$post->title}</h1>
	<div class="content smallinfo">
		<span itemprop="dateCreated" content="{$post->created}"></span>
		Publié le <span itemprop="datePublished" content="{$post->published}"><? HTime::simple($post->published) ?></span>
		{if!null $post->updated}<span itemprop="dateModified" content="{$post->updated}"></span>{/if}
	</div>
	
	<div itemprop="articleBody" class="clearfix">
	{=$post->content}
	</div>
	/* IF(blog_personalizeAuthors_enabled) */
	<div class="alignRight">
		Auteurs : <? implode(', ',array_map(function(&$author){return '<span itemprop="author">'.HHtml::link($author->name,$author->url).'</span>';},$post->authors)) ?>
	</div>
	/* /IF */
	
	{if!e $post->posts}
		<div class="sepTop">
			<h5>/* VALUE(blog_postLinked_title) */</h5>
			<ul>
				{f $post->posts as $lPost}<li>{link $lPost->title,$lPost->link()}</li>{/f}
			</ul>
		</div>
	{/if}
</div>
