<?php new PageView(_tC('Sign in')) ?>

<? CSession::flash('user/login') ?>
<div id="page">
<div class="content w840 clearfix" style="margin:20px auto 0">
	<div class="floatL w400 mr20">
		<h3>Connectez-vous</h3>
		{=$form=User::Form()->attrClass('big')->action('/site/login')}
		{=$form->fieldsetStart()}
		{=$form->input('email')->wp100()}
		{=$form->input('pwd')->wp100()}
		{=$form->submit(_tC('Sign in'))->container()->attrClass('center')}
		{=$form->end(false)}
		
		<div id="divLostPassword" class="mt20">
			<h3>Mot de passe perdu</h3>
			<div class="smallinfo italic mb10">Vous avez perdu votre mot de passe ?<br/>Nous pouvons vous en renvoyer un nouveau à votre adresse email.</div>
			{=$form=User::Form(false)->id('formLostPassword')->attrClass('big')->action('/users/ajaxLostPassword')}
			{=$form->fieldsetStart()}
			{=$form->input('email')->id('UserEmailLostPassword')}
			<? $form->input('phoneNumber')->label('Votre numéro de téléphone')->pattern('^0[1-9]([\.\-\s]*[0-9]{2}){4}$') ?>
			{=$form->end('Renvoyer un nouveau mot de passe')}
		</div>
	</div>
	<div class="floatL w400 ml20">
		<h3>Inscrivez-vous</h3>
		{=$form=User::Form()->id('formRegister')->attrClass('big')->action('/users/register')}
		{=$form->fieldsetStart()}
		{=$form->input('first_name')->wp100()}
		{=$form->input('last_name')->wp100()}
		{=$form->input('email')->wp100()->id('UserEmailRegister')->attr('data-ajaxcheck','email')}
		{=$form->input('confirm_email')->label("Confirmation de l'adresse email")->noName()->wp100()->id('UserEmailRegisterConfirm')->attr('data-same','#UserEmailRegister')}
		<br class="clear"/>
		<div class="italic">Le mot de passe sera envoyé à l'adresse ci-dessus.</div>
		{=$form->submit(_tC('Register'))->container()->attrClass('center')}
		{=$form->end(false)}
	</div>
</div>
</div>

<?php HHtml::jsReady('users.loginRegister()'); ?>
