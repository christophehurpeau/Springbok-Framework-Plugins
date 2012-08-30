<p>Bienvenue sur <?= Config::$projectName ?></p>

<p>
	Pour valider votre compte : {link 'cliquez-ici',$user->validEmailLink($uhe->code),array('entry'=>'index','fullUrl'=>true)}. Vous ne pouvez pas vous connecter tant que votre email n'est pas validé.
</p>

<p>Votre email : <b>{$user->email}</b>.<br />
	Votre mot de passe : <b>{$password}</b>.</p>
<p>Vous pourrez ensuite vous connecter sur {link null,'/',array('fullUrl'=>true)}</p>
<p>Votre mot de passe est modifiable dans votre compte.</p>