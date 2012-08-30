<p>Changement du courriel :</p>
<p>Vous avez demandé à changer votre courriel : <b>{$user->email}</b>.</p></p>
<p>Si ce n'est pas vous qui avez fait la demande et que vous ne souhaitez pas changer de courriel, cliquez sur : {link null,'/users/cancelChangeEmail/'.$user->id.'/'.urlencode($user->email).'/'.$uhe->code,array('fullUrl'=>true)}</p>