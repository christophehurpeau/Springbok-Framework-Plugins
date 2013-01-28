<?php new AjaxPageView($layout_title,'ml160') ?>


<div class="fixed left w160">
	{include _cmsMenu}
	<hr/>
	{includePlugin files/viewsLayouts/admin/_filesMenu}
</div>

<div class="variable padding">{=$layout_content}</div>