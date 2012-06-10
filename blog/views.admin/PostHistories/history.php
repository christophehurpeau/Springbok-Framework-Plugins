<?php new AjaxContentView('Historique n°'.$history->id); ?>

{iconLink 'back','Retour à l\'édition de l\'article','/posts/edit/'.$history->post_id,array('rel'=>'page')}

<div class="content mt10">Résumé</div>
{$history->excerpt}
<div class="content mt10">Contenu</div>
{$history->content}

<div class="mt10">
	{iconLink 'time_go','Restaurer cette version','/postHistories/restore/'.$history->id}
</div>