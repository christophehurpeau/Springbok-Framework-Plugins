<?php new AjaxContentView('Langs','Dev/default') ?>

{ife Config::$availableLangs} No langs.{else}
<div><h2><? $count=count(Config::$availableLangs)?> <?= _sp($count,'lang','langs') ?></h2>
	<ul>
{f Config::$availableLangs as $lang}
	<li>{$lang} : {link 'project',['/dev/:controller(/:action/*)?','langs','lang','/'.$lang]}
		 - {link 'project singular/plural',['/dev/:controller(/:action/*)?','langs','sp','/'.$lang]}
		 - {link 'Models',['/dev/:controller(/:action/*)?','langs','models','/'.$lang]}
		 - {link 'js',['/dev/:controller(/:action/*)?','langs','js','/'.$lang]}
		 - {link 'Plugins',['/dev/:controller(/:action/*)?','langs','plugins','/'.$lang]}
		 &nbsp; {iconAction 'delete',['/dev/:controller(/:action/*)?','langs','delete','/'.$lang]}</li>
{/f}
</ul></div>
{/if}