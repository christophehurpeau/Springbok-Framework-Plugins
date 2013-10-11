<?php new AjaxContentView('Changement de mot de passe'); HMeta::canonical(false); ?>

{if!e $message}
	<p class="message {$classMessage}">{$message}</p>
{else}
	<h3>Réinitialisation du mot de passe</h3>
	<p>Saisissez ci-dessous le nouveau mot de passe que vous souhaitez avoir :</p>
	{=$form=User::Form(false)->attrClass('big')->action('/users/changePassword/'.$userId.'/'.$email.'/'.$code)}
	{=$form->fieldsetStart()}
	{=$form->input('pwd')->id('UserNewPassword')}
	{=$form->input('pwd')->noName()->label('Confirmation')->id('UserNewPasswordConfirm')->dataattr('same','#UserNewPassword')}
	{=$form->submit('Changer de mot de passe')->container()->addClass('center')}
	{=$form->end(false)}
{/if}