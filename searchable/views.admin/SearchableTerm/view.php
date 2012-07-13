<?php new AjaxContentView('Term: '.$term->term) ?>

/* IF(searchable.keywordTerms.text) */<div class="floatR w300">/* /IF */

<div class="/* IF!(searchable.keywordTerms.text) */floatR /* /IF */block2">
	<div>Created : <? HTime::compact($term->created) ?></div>
	<div>Type : {$term->type()}</div>
</div>

<div id="linkedKeywords" class="clear mt10 block1">
	<h5 class="noclear">{t 'plugin.searchable.LinkedKeywords'}</h5>
	<? HHtml::ajaxCRDInputAutocomplete('/searchableTerm',$term->keywords,array('url'=>'/'.$term->id)) ?>
</div>

/* IF(searchable.keywordTerms.text) */</div>/* /IF */
<?php $form=HForm::create('SearchablesTerm',array('id'=>'formTermEdit','name'=>'term'),'div',false) ?>


<div class="mr300 context">
	{=$form->input('term',array('class'=>'wp100 biginfo'),array('class'=>'input text mb10'))}
	
	/* IF(searchable.keywordTerms.seo) */
	<? View::element('seo',array('model'=>$term,'form'=>$form)) ?>
	{=$form->submit(true,array(),array('class'=>'submit center'))}
	/* /IF */
</div>

/* IF(searchable.keywordTerms.text) */
<div class="clear">
	<h4>Description du terme</h4>
	{=$form->textarea('text',array('class'=>'wp100'))}
	{=$form->submit(true,array(),array('class'=>'submit center'))}
</div>
/* /IF */
{=$form->end(false)}

<? HHtml::jsInline('S.ready(function(){/* IF(searchable.keywordTerms.seo) */_.seo.init($(\'#SearchablesTermSeo\')/*,$(\'#linkedTerms ul\')*/);/* /IF */'
	.'/* IF(searchable.keywordTerms.text) */S.tinymce.init("100%","330px","basicAdvanced",!!_.cms).wordCount().autolink().autoSave().validXHTML()'
		/* IF2(searchable.keywordTerms.seo) */.'.addAttr("onchange_callback",_.seo.tinymceChanged_metaKeywords)'/* /IF2 */
		.'.createForId("SearchablesTermText");/* /IF */'
	.'$("#formTermEdit").ajaxForm(basedir+"searchableTerm/save/'.$term->id.'",false,function(){'
		//.'if($("#SearchablesKeywordDescr").val()==""){alert("Le texte est vide !");return false;}'
	.'});});') ?>
<br class="clear"/>
