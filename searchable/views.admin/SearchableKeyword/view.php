<?php new AjaxContentView('Keyword: '.$keyword->name()) ?>

{=$form=SearchablesKeyword::Form('keyword')->id('formKeywordEdit')->noDefaultLabel()}

/* IF(searchable.keywords.text) */
<div class="row gut mt10">
	<div class="col w300">
/* /IF */
		<div class="/* IF!(searchable.keywords.seo) */ /* /IF */ block2">
			<div>Created : <? HTime::compact($keyword->created) ?></div>
			<div>Types: <?php $types=SearchablesTypedTerm::typesList(); ?>
				{f $keyword->types as $type}
					{=?e $types[$type] : $type}, 
				{/f}
			</div>
			<div class="mt6">{link 'Go to the term','/searchableTerm/view/'.$keyword->id}</div>
		</div>
		
		{include _linkedTerms}

/* IF(searchable.keywords.text) */
	</div>
/* /IF */


	<div class="col">
		{=$form->input('term')->attrClass('wp100 biginfo')->required()->container()->addClass('mb10')}
		
		/* IF(searchable.keywords.seo) */
		<? View::element('seo',array('model'=>$keyword,'form'=>$form)) ?>
		/* /IF */
		{=$form->submit(true)->container()->addClass('center')}
		
		
		/* IF(searchable.keywords.text) */
		<div class="mt10">
			<h4>Description du mot clé</h4>
			{=$form->textarea('text')->wp100()}
			{=$form->submit(true)->container()->addClass('center')}
		</div>
		/* /IF */
	</div>

/* IF(searchable.keywords.text) */
</div>
/* /IF */
{=$form->end(false)}

<?php HHtml::jsReady('/* IF(searchable.keywords.seo) */_.seo.init($(\'#SearchablesKeywordSeo\'),$(\'#linkedTerms ul\'));/* /IF */'
	.'/* IF(searchable.keywords.text) */S.tinymce.init("100%","330px","basicAdvanced",!!_.cms).wordCount().autolink().autoSave().validXHTML()'
		/* IF2(searchable.keywords.seo) */.'.addAttr("onchange_callback",_.seo.tinymceChanged_metaKeywords)'/* /IF2 */
		.'.createForId("SearchablesKeywordText");'
	.'$("#formKeywordEdit").ajaxForm(basedir+"searchableKeyword/save/'.$keyword->id.'",false,function(){'
		//.'if($("#SearchablesKeywordDescr").val()==""){alert("Le texte est vide !");return false;}'
	.'});/* /IF */'
) ?>
<br class="clear"/>