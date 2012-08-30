<?php new AjaxContentView('Validation d\'un nouveau courriel'); HMeta::canonical(false); ?>

<p class="message {$classMessage}">{$message}</p>
{if $classMessage==='mWarning'}{link 'Confirmer','?confirm=1',array('class'=>'button')}{/if}
