<?php new AjaxContentView('Mes informations'); HMeta::canonical(false);
HBreadcrumbs::set(array('Mon compte'=>'/user'));
?>

<? CSession::flash('user/me','p') ?>
{*<h1>Mes Informations</h1>*}

<?php $disabled = $user->type!==User::SITE; ?>
{=$form=User::Form()}
{=$form->input('first_name')->disabled($disabled)}
{=$form->input('last_name')->disabled($disabled)}
/*#if users.pseudo*/
{=$form->input('pseudo')->dataattr('ajaxcheck'=>'pseudo')->required()->dataattr('data-checkexception'=>$user->pseudo)}
/*#/if*/
{if $user->type!==User::SITE} {=$form->input('gender')->disabled()->value($user->gender())}
{else} {=$form->select('gender',SConsts::gender())} {/if}
{=$form->input('email')->disabled($disabled)->dataattr('ajaxcheck'=>'email')->required()->dataattr('data-checkexception'=>$user->email)}
{=$form->end()}

{if $user->type!==User::SITE}
	<br/><p class="message info">{icon info}Certaines informations proviennent de services tiers et ne peuvent pas être directement modifiées.<br/>
		<span class="smallinfo">Si vous souhaitez les changer, faites le dans le {link 'service concerné',$user->serviceLink()}, puis déconnectez-vous et reconnectez-vous sur Le Guide Santé.</span></p>
{else}
	<h2 class="mt20">Changer votre mot de passe</h2>
	
	{if!e $errorPasswordChange}
	<p class="message error">{$errorPasswordChange}</p>
	{/if}
	
	{=User::Form()->action('/user/changePassword')}
	{=$form->input('old_password')->label('Ancien mot de passe')->setType('password')->required()->attr('autocomplete','off')->pattern('.{4,}')}
	{=$form->input('new_password')->label('Nouveau mot de passe')->setType('password')->required()->datattr('min-length',6)
				->attr('autocomplete','off')->pattern('^(.*[A-Za-z]+.*[0-9]+.*|.*[0-9]+.*[A-Za-z]+.*)$')}
	{=$form->input('new_password_confirm')->label('Confirmation')->setType('password')->attr('autocomplete','off')->datattr('same','#new_password')}
	{=$form->end('Changer')}
	
	<p class="message info">Votre mot de passe doit avoir un minimum de <b>6</b> caractères. Il doit comporter des chiffres et des lettres. Tous les caractères sont autorisés.</p>
{/if}

{*
<div class="block1 mt20">
	{link 'Supprimer définitivement ce compte.','/user/delete'}
</div>
*}