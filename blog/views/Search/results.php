<?php new AjaxView('Résultats de recherche','blog'); HMeta::canonical('/search') ?>
{*{if $noresults!==false}
<div class="noresults">
	Aucun résultat trouvé.
{else}
<div class="nbresults">
	<? number_format($totalResults,0,'',' ').' résultat'.($totalResults==1?'':'s').' trouvé'.($totalResults==1?'':'s') ?>
{/if}
</div>*}

<? $divPagination=$hSearch->pager() ?>
<ul class="nobullets cSepTop">
{f $result->pagination->getResults() as $post}<? View::element('post',array('post'=>$post)) ?>{/f}
</ul>
{=$divPagination}