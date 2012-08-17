<?php $v=new AjaxContentView('Menu','admin/cms') ?>

<? HHtml::ajaxCRDInputAutocomplete('/cmsMenu',$menu,array('ulAttributes'=>array('class'=>'nobullets cMt10 mt10 sortable'))) ?>
{jsReady}
$( ".sortable" ).sortable({
	placeholder: "ui-state-highlight",
	update: function(){
		$.post(basedir+'cmsMenu/sort',$(this).sortable("serialize",{key:'pages[]',attribute:'rel',expression:'(.*)'}));
	}
}).disableSelection().change(function(){$(this).sortable('refresh')});
{/jsReady}