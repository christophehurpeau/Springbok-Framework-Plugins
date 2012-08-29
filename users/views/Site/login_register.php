<?php new PageView(_tC('Sign in')) ?>

<? CSession::flash('user/login') ?>
<div id="page">
<div class="content w640 clearfix" style="margin:20px auto 0">
	<div class="floatL w300 mr20">
		<h3>Connectez-vous</h3>
		{=$form=User::Form()->attrClass('big')->action('/site/login')}
		{=$form->fieldsetStart()}
		{=$form->input('email')->wp100()}
		{=$form->input('pwd')->wp100()}
		{=$form->submit(_tC('Sign in'))->container()->attrClass('center')}
		{=$form->end(false)}
	</div>
	<div class="floatL w300 ml20">
		<h3>Inscrivez-vous</h3>
		<?php
		$form=HForm::create('User',array('id'=>'formRegister','class'=>'big','action'=>'/site/register'));
		echo $form->fieldsetStart();
		echo $form->input('email',array('label'=>'Courriel','id'=>'UserEmailInscription','data-ajaxcheck'=>'email'),array('error'=>isset($_POST['user']['email'])?'Votre courriel n\'est pas valide.':false));
		echo $form->input('first_name',array('label'=>'Prénom','required'=>true),array('error'=>isset($_POST['user']['first_name'])?'Entrez votre prénom.':false));
		echo $form->input('last_name',array('label'=>'Nom','required'=>true),array('error'=>isset($_POST['user']['last_name'])?'Entrez votre nom.':false));
		echo '<br class="clear"/><div class="italic">Le mot de passe sera envoyé à l\'adresse ci-dessus.</div>';
		$form->end('Inscription');
		?>
	</div>
</div>
</div>