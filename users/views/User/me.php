<?php new AjaxContentView('Mes informations'); HMeta::canonical(false);
HBreadcrumbs::set(array('Mon compte'=>'/user'));
?>

<? CSession::flash('user/me','p') ?>
{*<h1>Mes Informations</h1>*}

<?php $form=HForm::create('User') ?>
<?php $attrs=$user->type!==User::SITE ? array('disabled'=>true) : array(); ?>
{=$form->input('first_name',$attrs)}
{=$form->input('last_name',$attrs)}
/*#if users.pseudo*/
{=$form->input('pseudo',array('data-ajaxcheck'=>'pseudo','required'=>true,'data-checkexception'=>$user->pseudo))}
/*#/if*/
{if $user->type!==User::SITE}{=$form->input('gender',array('disabled'=>true,'value'=>$user->gender()))}
{else}{=$form->select('gender',SConsts::gender())}{/if}
{=$form->input('email',array('data-ajaxcheck'=>'email','required'=>true,'data-checkexception'=>$user->email)+$attrs)}
{=$form->end()}

{if $user->type!==User::SITE}
	<br/><p class="message info">{icon info}Certaines informations proviennent de services tiers et ne peuvent pas être directement modifiées.<br/>
		<span class="smallinfo">Si vous souhaitez les changer, faites le dans le {link 'service concerné',$user->serviceLink()}, puis déconnectez-vous et reconnectez-vous sur Le Guide Santé.</span></p>
{else}
	<h2 class="mt20">Changer votre mot de passe</h2>
	
	{if!e $errorPasswordChange}
	<p class="message error">{$errorPasswordChange}</p>
	{/if}
	
	<?php $form=HForm::create(null,array('action'=>'/user/changePassword')) ?>
	<? $form->input('old_password',array('label'=>'Ancien mot de passe','type'=>'password','required'=>true,'autocomplete'=>'off','pattern'=>'.{4,}')) ?>
	<? $form->input('new_password',array('label'=>'Nouveau mot de passe','type'=>'password','required'=>true,'data-min-length'=>6,'autocomplete'=>'off','pattern'=>'^(.*[A-Za-z]+.*[0-9]+.*|.*[0-9]+.*[A-Za-z]+.*)$')) ?>
	<? $form->input('new_password_confirm',array('label'=>'Confirmation','type'=>'password','autocomplete'=>'off','data-same'=>'#new_password')) ?>
	{=$form->end('Changer')}
	
	<p class="message info">Votre mot de passe doit avoir un minimum de <b>6</b> caractères. Il doit comporter des chiffres et des lettres. Tous les caractères sont autorisés.</p>
{/if}

{*
<div class="block1 mt20">
	{link 'Supprimer définitivement ce compte.','/user/delete'}
</div>
*}