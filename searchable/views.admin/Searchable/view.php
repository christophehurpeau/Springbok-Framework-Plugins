<?php new AjaxContentView($sb->name) ?>

{=$table->display()}

<div class="row gut sepTop">
	<div class="col wp40">
		<h5>Words</h5>
		{=$tableWords->display(false)}
	</div>
	<div class="col wp40">
		<h5>Keywords</h5>
		{=$tableKeywords->display(false)}
	</div>
	<div class="col wp20">
		<div class="block2">
			<h5>Actions</h5>
			{link 'Reindex','/searchable/reindex/'.$sb->id}
		</div>
	</div>
</div>