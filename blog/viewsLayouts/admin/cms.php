<?php new AjaxPageView($layout_title,'ml160') ?>


<div class="fixed left w160">
	{menuLeft 'startsWith':true/*,'menuAttributes':array('rel'=>'page')*/
		_t('plugin.blog.Posts')=>array('/posts','startsWith'=>false),
		/* IF(blog_personalizeAuthors_enabled) */
		_t('plugin.blog.Authors')=>'/postsAuthors',
		/* /IF */
		_t('plugin.blog.Tags')=>'/postsTags',
		_t('plugin.blog.Categories')=>'/postsCategories',
		/* IF(blog_comments_enabled) */
		_t('plugin.blog.Comments')=>array('/postsComments','startsWith'=>false),
		_t('plugin.blog.CommentValidation')=>'/postsComments/validation',
		/* /IF */
		false,
		_t('plugin.blog.Tools')=>'/posts/tools',
	}
	<hr/>
	{includePlugin cms/viewsLayouts/admin/_cmsMenu}
	<hr/>
	{includePlugin files/viewsLayouts/admin/_filesMenu}
</div>

<div class="variable padding">{=$layout_content}</div>