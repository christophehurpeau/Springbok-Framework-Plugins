<?php new AjaxContentView('DevDb') ?>
<?php $table->display() ?>

{f $relations as $relName => $tableRel}
	<div class="block1 mt20">
		<h5>{$relName}</h5>
		<?php $tableRel->display() ?>
	</div>
{/f}
		

{*f $belongsTo as $b}
	{debug $b}
{/f}

{f $hasMany as $key=>$hm}
	<div class="block1">
		<h5>{$key} {$hm['count']}</h5>
		{if!e $hm['results']}
		<table class="table">
			{f $hm['results'] as $r}
				<tr>
				{f $r as $rk=>$rv}
				<td>{$rk}={$rv}</td>
				{/f}
				</tr>
			{/f}
		</table>
		{else}
			{tC 'No result'}
		{/if}
	</div>
{/f*}
