<?php new AjaxBaseView($layout_title,'Dev/base') ?>
<header>
	<div id="logo"><?= Config::$projectName ?></div> 
	{menuTop 'startsWith':true
		'Retour':false,
		'Langs':[['/dev/:controller(/:action/*)?','langs',null,'']]
	}
</header>
{=$layout_content}
<footer><? HHtml::powered() ?></footer>