<?php new AjaxContentView('Outils','admin-posts');
HBreadcrumbs::set(array(
	'Articles'=>'/posts',
)); ?>

{menuLeft
	'Regénérer la liste des derniers articles'=>'/posts/regenerateLatest',
}
