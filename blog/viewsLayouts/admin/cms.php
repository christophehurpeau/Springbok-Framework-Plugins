<?php new AjaxPageView($layout_title,'') ?>


<div class="col fixed left w160">
	{menuLeft 'startsWith':true/*,'menuAttributes':array('rel'=>'page')*/
		_t('plugin.blog.Posts')=>array('/posts','startsWith'=>false),
		/*#if blog_personalizeAuthors_enabled*/
		_t('plugin.blog.Authors')=>'/postsAuthors',
		/*#/if*/
		_t('plugin.blog.Tags')=>'/postsTags',
		_t('plugin.blog.Categories')=>'/postsCategories',
		/*#if blog_comments_enabled*/
		_t('plugin.blog.Comments')=>array('/postsComments','startsWith'=>false),
		_t('plugin.blog.CommentValidation')=>'/postsComments/validation',
		_t('plugin.connectedSite.ConnectedServices')=>array('/connectedServices'),
		/*#/if*/
		false,
		_t('plugin.blog.Tools')=>'/posts/tools',
	}
	<hr/>
	{includePlugin cms/viewsLayouts/admin/_cmsMenu}
	<hr/>
	{includePlugin files/viewsLayouts/admin/_filesMenu}
</div>

<div class="col variable l160">{=$layout_content}</div>