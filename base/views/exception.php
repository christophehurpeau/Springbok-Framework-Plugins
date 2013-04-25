<?php new AjaxContentPageView('Erreur'); ?>
<div class="variable padding">
	<h1>Erreur</h1>
	<div class="content">
		<p>{if $e instanceof HttpException && $e->hasDescription()}{$e->getDescription()}{else}L'application a rencontré une erreur ({$e_className}){/if}.<br />
		Si le problème persiste, contactez le support technique.</p>
		<?php /* DEV */ HDev::exception($e)/* /DEV */ ?>
	</div>
</div>