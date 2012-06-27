<?php new AjaxContentView('Keyword: '.$keyword->name) ?>

/* IF(searchable_seo) */<div class="float_right w300">/* /IF */

<div class="/* IF!(searchable_seo) */float_right /* /IF */block2">
	<div>Type : <? _tF($keyword->_type(),'') ?></div>
	<div>Created : <? HTime::compact($keyword->created) ?></div>
</div>

<div id="linkedTerms" class="clear mt10 block1">
	<h5 class="noclear">{t 'plugin.searchable.LinkedTerms'}</h5>
	<? HHtml::ajaxCRDInputAutocomplete('/searchableKeyword',$keyword->terms,array('allowNew'=>1,'url'=>'/'.$keyword->id)) ?>
</div>

/* IF(searchable_seo) */</div>
<?php $form=HForm::create('SearchablesKeyword',array('id'=>'formKeywordEdit','name'=>'keyword'),'div',false) ?>
<div class="mr300 context">
	{=$form->input('name',array('class'=>'wp100 biginfo'),array('class'=>'input text mb10'))}
	
	<? View::element('seo',array('model'=>&$keyword,'form'=>&$form)) ?>
	{=$form->submit(true,array(),array('class'=>'submit center'))}
</div>

<div class="clear">
	<h4>Description</h4>
	{=$form->textarea('descr',array('class'=>'wp100'))}
	{=$form->submit(true,array(),array('class'=>'submit center'))}
</div>
{=$form->end(false)}

<? HHtml::jsInline('S.ready(function(){_.seo.init($(\'#SearchablesKeywordName\'),$(\'#linkedTerms ul\'));'
	.'S.tinymce.init("100%","250px","basicAdvanced",true).wordCount().autolink().autoSave().validXHTML()'
		.'.addAttr("onchange_callback",_.seo.tinymceChanged_metaKeywords)'
		.'.addAttr("internalLinks",_.posts.internalLinks).createForId("SearchablesKeywordDescr");'
	.'$("#formKeywordEdit").ajaxForm(basedir+"searchableKeyword/save/'.$keyword->id.'",false,function(){'
		//.'if($("#SearchablesKeywordDescr").val()==""){alert("Le texte est vide !");return false;}'
	.'});});') ?>
/* /IF */
<br class="clear"/>
