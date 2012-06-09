<?php new AjaxPageView($layout_title,'ml200') ?>


<div class="fixed left w200">
	{menuLeft 'startsWith':true/*,'menuAttributes':array('rel'=>'page')*/
		'Articles'=>array('url'=>'/posts','startsWith'=>false),
		'Auteurs'=>'/postsAuthors',
		'Mots-clés'=>'/postsTags',
		'Catégories'=>'/postsCategories',
		'Commentaires'=>array('url'=>'/postsComments','startsWith'=>false),
		'Validation Commentaires'=>'/postsComments/validation',
		false,
		'Outils'=>'/posts/tools',
	}
</div>

<div class="variable padding">{=$layout_content}</div>