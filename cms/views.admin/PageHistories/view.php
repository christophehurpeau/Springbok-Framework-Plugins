{if $history->getTotalResults()===0}
	<i>Pas d'historique.</i>
{else}
	{=$pager=HPagination::simple($history)}
	<ul class="compact">
		{f $history->getResults() as $h}
		<li><? HHtml::link(HTime::simple($h->created),'/pageHistories/history/'.$h->id,array('rel'=>'page')) ?></li>
		{/f}
	</ul>
	{=$pager}
{/if}