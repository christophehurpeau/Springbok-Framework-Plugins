<?php new AjaxPageView($layout_title,'','Dev/page') ?>
<div class="col fixed left w280">
	<ul>
	{f $deployments as $key=>$value}
		<li>{$key}</li>
	{/f}
	</ul>	
</div>
<div class="col variable l280">
	<h1>{$layout_title}</h1>
	{=$layout_content}
</div>