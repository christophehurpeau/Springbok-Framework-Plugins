<?php new AjaxPageView($layout_title,'','Dev/page') ?>
<div class="col fixed left w280">
	<ul class="simpleDouble ml10">
	{f $models as $dbName=>$imodels}
		<?php sort($imodels) ?>
		<li><h5>{$dbName}</h5>
			<ul class="compact">
				{f $imodels as $modelName}<li>{link $modelName,['/dev/:controller(/:action/*)?','db','model','/'.$modelName]}</li>{/f}
			</ul>
		</li>
	{/f}
	</ul>
</div>
<div class="col variable l280">
	<h1>{$layout_title}</h1>
	{=$layout_content}
</div>