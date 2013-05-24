<?php new AjaxContentView('DevTests') ?>

<ul class="simpleDouble ml10">
<?php UPhp::recursive(function($callback,$tests) use($allResults){ ?>
	{f $tests as $path=>$file}
		{if $file->isDir()}
		<li>
			<h4>{$file->getFilename()}</h4>
			<ul>
				<?php $callback($callback,new RecursiveDirectoryIterator($path,FilesystemIterator::SKIP_DOTS)) ?>
			</ul>
		</li>
		{else}
			<li>
				<h5>{$file->getFilename()}</h5>
				<?php STest::display($allResults[$path]) ?>
			</li>
		{/if}
	{/f}
<?php },$tests) ?>
</ul>