<?php new AjaxBreadcrumbsPageView($layout_title,'ml160','admin-page') ?>

<div class="fixed left w160">
	{menuLeft
		'Searchable'=>'/searchable',
		'Keywords'=>'/searchable/keywords',
	}
</div>

<div class="variable padding">
	{=$layout_content}
</div>