<?php new AjaxContentPageView('Erreur'); ?>
<div class="variable padding"><h1>Erreur</h1>
	<div class="content">
		<p>L'application a rencontré une erreur.<br />
		Si le problème persiste, contactez le support technique.</p>
		<?php /*#if DEV */ HDev::error($e_message, $e_file, $e_line, $e_context) /*#/if*/ ?>
	</div>
</div>