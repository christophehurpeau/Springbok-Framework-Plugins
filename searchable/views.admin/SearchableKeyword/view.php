<?php new AjaxContentView('Keyword: '.$keyword->name()) ?>

/* IF(searchable.keywords.text) */<div class="floatR w300">/* /IF */

<div class="/* IF!(searchable.keywords.seo) */floatR /* /IF */block2">
	<div>Created : <? HTime::compact($keyword->created) ?></div>
	<div>Type : <? _tF($keyword->_type(),'') ?></div>
</div>

<div id="linkedTerms" class="clear mt10 block1">
	<h5 class="noclear">{t 'plugin.searchable.LinkedTerms'}</h5>
	<? HHtml::ajaxCRDInputAutocomplete('/searchableKeyword',$keyword->terms,
			array('js'=>'{allowNew:1,url:"/'.$keyword->id.'"}','modelFunctionName'=>'adminLinkWithType','escape'=>false)) ?>
</div>


/* IF(searchable.keywords.text) */</div>/* /IF */
<?php $form=HForm::create('SearchablesKeyword',array('id'=>'formKeywordEdit','name'=>'keyword'),'div',false) ?>


<div class="mr300 context">
	{=$form->input('term',array('class'=>'wp100 biginfo'),array('class'=>'input text mb10'))}
	
	/* IF(searchable.keywords.seo) */
	<? View::element('seo',array('model'=>$keyword,'form'=>$form)) ?>
	/* /IF */
	{=$form->submit(true,array(),array('class'=>'submit center'))}
</div>

/* IF(searchable.keywords.text) */
<div class="clear">
	<h4>Description du mot clé</h4>
	{=$form->textarea('text',array('class'=>'wp100'))}
	{=$form->submit(true,array(),array('class'=>'submit center'))}
</div>
{=$form->end(false)}
/* /IF */

<?php HHtml::jsReady('/* IF(searchable.keywords.seo) */_.seo.init($(\'#SearchablesKeywordSeo\'),$(\'#linkedTerms ul\'));/* /IF */'
	.'/* IF(searchable.keywords.text) */S.tinymce.init("100%","330px","basicAdvanced",!!_.cms).wordCount().autolink().autoSave().validXHTML()'
		/* IF2(searchable.keywords.seo) */.'.addAttr("onchange_callback",_.seo.tinymceChanged_metaKeywords)'/* /IF2 */
		.'.createForId("SearchablesKeywordText");'
	.'$("#formKeywordEdit").ajaxForm(basedir+"searchableKeyword/save/'.$keyword->id.'",false,function(){'
		//.'if($("#SearchablesKeywordDescr").val()==""){alert("Le texte est vide !");return false;}'
	.'});/* /IF */'
) ?>
<br class="clear"/>