<?php new AjaxPageView($layout_title,'ml200') ?>


<div class="fixed left w200">
	{menuLeft 'startsWith':true/*,'menuAttributes':array('rel'=>'page')*/
		'Articles'=>array('url'=>'/posts','startsWith'=>false),
		/* IF(blog_personalizeAuthors_enabled) */
		'Auteurs'=>'/postsAuthors',
		/* /IF */
		'Mots-clés'=>'/postsTags',
		'Catégories'=>'/postsCategories',
		/* IF(blog_comments_enabled) */
		'Commentaires'=>array('url'=>'/postsComments','startsWith'=>false),
		'Validation Commentaires'=>'/postsComments/validation',
		/* /IF */
		false,
		'Outils'=>'/posts/tools',
	}
</div>

<div class="variable padding">{=$layout_content}</div>