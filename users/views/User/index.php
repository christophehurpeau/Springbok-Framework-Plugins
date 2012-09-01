<?php new AjaxContentView('Mon compte'); HMeta::canonical(false);
HBreadcrumbs::set(array('Mon compte'=>'/user'));
?>

<ul class="clickable nobullets cMt10 linksList">
	<li class="block1">
	{linkHtml '<b>Mes informations personnelles</b><br />Modifiez vos données personnelles.','/user/me'}
	</li>
</ul>