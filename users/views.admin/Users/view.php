<?php new AjaxContentView('Utilisateur : '.($name=$user->name()));
HBreadcrumbs::set(array('Utilisateurs'=>'/users'),$name) ?>
<h1>{$name}</h1>

<div class="content">
	Id : <b>{$user->id}</b><br/>
	Email : <b>{$user->email}</b><br/>
	Pseudo : <b>{$user->pseudo}</b><br/>
	Nom : <b>{$user->first_name} {$user->last_name}</b><br/>
	Sexe : <b>{$user->gender()}</b><br/>
	Type : <b>{$user->type()}</b> - Statut : <b>{$user->status()}</b><br/>
	Date de création : <b><? HTime::nice($user->created) ?></b>{if!null $user->updated} - Date de mise à jour : <b><? HTime::nice($user->updated) ?></b>{/if}
</div>

<div class="row gut sepTop">
	<div class="col wp50">
		<h4>Historique du compte</h4>
		{if $user->history->isEmptyResults()}
			<p class="mWarning">Aucun historique pour ce compte.</p>
		{else}
			<ul class="simpleDouble">
			<?php $lastDay=null ?>
			{f $user->history->getResults() as $h}
				<?php $day=HTime::nice($h->created,false); ?>
				{if $lastDay!==$day}{if $lastDay!==null}</ul>{/if}<?php $lastDay=$day ?><li class="bold biginfo">Le {$day}</li><ul>{/if}
				<li><i><? HTime::hoursAndMinutes($h->created) ?></i> - <b>{$h->detailOperation()}</b>{$h->details()}</li>
			{/f}
			</ul></ul>
		{/if}
	</div>
	<div class="col wp50">
		<h4>Historique de l'espace PRO</h4>
		{if $user->orgHistory->isEmptyResults()}
			<p class="mWarning">Aucun historique pour ce compte.</p>
		{else}
			<ul class="simpleDouble">
			<?php $lastDay=null ?>
			{f $user->orgHistory->getResults() as $h}
				<?php $day=HTime::nice($h->created,false); ?>
				{if $lastDay!==$day}{if $lastDay!==null}</ul>{/if}<?php $lastDay=$day ?><li class="bold biginfo">Le {$day}</li><ul>{/if}
				<li><i><? HTime::hoursAndMinutes($h->created) ?></i> - <span class="smallinfo">pour</span> {link $h->organization->name,'/bookOrganizations/view/'.$h->organization->id} - <b>{$h->detailOperation()}</b>{=$h->details()}</li>
			{/f}
			</ul></ul>
		{/if}
	</div>
</div>