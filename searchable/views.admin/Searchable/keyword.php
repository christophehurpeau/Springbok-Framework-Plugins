<?php new AjaxContentView('Keyword: '.$keyword->name) ?>

<div class="float_right block2">
	Created : <? HTime::compact($keyword->created) ?>
</div>

<h5 class="noclear">Linked terms</h5>

<? HHtml::ajaxCRDInputAutocomplete('/searchable',$keyword->terms,array('allowNew'=>1,'url'=>'/'.$keyword->id)) ?>