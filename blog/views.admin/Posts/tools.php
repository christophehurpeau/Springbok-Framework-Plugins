<?php new AjaxContentView('Outils');
HBreadcrumbs::set(array(
	'Articles'=>'/posts',
)); ?>

{menuLeft
	'Regénérer la liste des derniers articles'=>'/posts/regenerateLatest',
	'Regénérer le sitemap'=>'/posts/regenerateSitemap',
}
