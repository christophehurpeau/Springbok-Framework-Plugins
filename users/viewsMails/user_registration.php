<p>Bonjour,</p>
<p>Bienvenue sur <?= Config::$projectName ?></p>

<p>Pour valider votre compte : {link 'cliquez-ici',$user->validEmailLink($uhe->code),array('entry'=>'index','fullUrl'=>true)}.</p>
<p>Votre email : <b>{$user->email}</b>.{if!e $generatedPassword}<br />
	Votre mot de passe : <b>{$password}</b>.{/if}</p>
<p>Vous pourrez ensuite vous connecter sur {link null,'/',array('fullUrl'=>true)}</p>
<p>Vous pouvez changer votre mot de passe dans votre compte.</p>

<p>A bientôt !</p>