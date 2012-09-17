<?php new AjaxContentView('Langs','Dev/default') ?>


{ife App::$availableLangs} No langs.{else}
<div><h2><? $count=count(App::$availableLangs)?> <?= _sp($count,'lang','langs') ?></h2>
	<ul>
{f App:$availableLangs as $lang}
	<li>{$lang} : {link 'project',['/dev/:controller(/:action/*)?','devLangs','lang',$lang]}
		 - {link 'project singular/plural','projectLangs','sp',$lang]}
		 - {link 'Models',['/dev/:controller(/:action/*)?','devLangs','models',$lang]}
		 - {link 'js',['/dev/:controller(/:action/*)?','devLangs','js',$lang]}
		 - {link 'Plugins',['/dev/:controller(/:action/*)?','devLangs','plugins',$lang]}
		 &nbsp; {iconAction 'delete',['/dev/:controller(/:action/*)?','devLangs','delete',$lang]}</li>
{/f}
</ul></div>
{/if}