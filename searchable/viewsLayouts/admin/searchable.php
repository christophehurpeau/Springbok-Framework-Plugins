<?php new AjaxPageView($layout_title,'','admin_page') ?>

<div class="col fixed left w160">
	{menuLeft
		'Searchable'=>'/searchable',
		'Keywords'=>'/searchable/keywords',
		'Terms'=>'/searchable/terms',
	}
</div>

<div class="col variable l160">
	<?php HBreadcrumbs::display(_tC('Home'),$layout_title) ?>
	{=$layout_content}
</div>