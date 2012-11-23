<?php new AjaxContentView('Historique n°'.$history->id); ?>

{iconLink 'back','Retour à l\'édition de la page','/pages/edit/'.$history->page_id,array('rel'=>'page')}

<div class="content mt10">Contenu</div>
{$history->content}

<div class="mt10">
	{iconLink 'timeGo','Restaurer cette version','/pageHistories/restore/'.$history->id}
</div>