<?php new AjaxPageView($layout_title,'','Dev/page') ?>
<div class="col fixed left w280">
	<div class="content mtb10">
		{link 'Exécuter tous les tests',['/dev/:controller(/:action/*)?','tests','all','']}
	</div>
	<ul class="simpleDouble ml10">
	<?php $len=strlen($tests->getPath())+1;
	UPhp::recursive(function($callback,$tests) use($len){ ?>
		{f $tests as $path=>$file}
			{if $file->isDir()}
			<li>
				<h5>{$file->getFilename()}</h5>
				<ul>
					<?php $callback($callback,new RecursiveDirectoryIterator($path,FilesystemIterator::SKIP_DOTS)) ?>
				</ul>
			</li>
			{else}
				<li>{link $file->getFilename(),['/dev/:controller(/:action/*)?','tests','view','','?'=>'file='.urlencode(substr($path,$len))]}</li>
			{/if}
		{/f}
	<?php },$tests) ?>
	</ul>
</div>
<div class="col variable l280">
	<h1>{$layout_title}</h1>
	{=$layout_content}
</div>