<?php new AjaxBaseView($layout_title,'Dev/base') ?>
<header>
	<div id="logo"><?= Config::$projectName ?></div> 
	{menuTop 'startsWith':true
		'Retour':false,
		'Langs':[['/dev/:controller(/:action/*)?','langs',null,'']],
		'Dbs':[['/dev/:controller(/:action/*)?','db',null,'']],
		'Tests':[['/dev/:controller(/:action/*)?','tests',null,'']],
		'Deployments':[['/dev/:controller(/:action/*)?','deployments',null,'']]
	}
</header>
{=$layout_content}
<footer><? HHtml::powered() ?></footer>