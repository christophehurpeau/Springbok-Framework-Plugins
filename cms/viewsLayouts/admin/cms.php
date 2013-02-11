<?php new AjaxPageView($layout_title,'') ?>


<div class="col fixed left w160">
	{include _cmsMenu}
	<hr/>
	{includePlugin files/viewsLayouts/admin/_filesMenu}
</div>

<div class="col variable l160">{=$layout_content}</div>