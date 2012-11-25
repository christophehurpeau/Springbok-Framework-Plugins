<div class="right">
	{if CSecure::isConnected()}
		<b>{=CSecure::user()->name()}</b><br/>
		{link _t('plugin.users.My account'),'/user'} - {link _tC('Sign out'),'/site/logout'}
	{else}
		{link _tC('Sign in'),'/site/login'}
	{/if}
</div>