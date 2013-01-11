<?php new AjaxContentView('Term: '.$term->term) ?>

{=$form=SearchablesTerm::Form('term')->id('formTermEdit')->noDefaultLabel()}

/* IF(searchable.keywordTerms.text) */<div class="floatR w300">/* /IF */


<div class="/* IF!(searchable.keywordTerms.text) */floatR /* /IF */block2">
	<div>Created : <? HTime::compact($term->created) ?></div>
	
	<?php $types=$typesDefault=SearchablesTypedTerm::typesList(); ?>
	
	<div>
		<?php if($term->type!==0) unset($typesDefault[0]); unset($typesDefault[SearchablesTypedTerm::ITSELF]) ?>
		{=$form->select('type',$typesDefault,$term->type)->label('Default type:')}
		{=$form->submit(true,array(),array('class'=>'submit center'))}
	</div>
	
	<div>
		Types: 
		{f $term->types as $type}
			{if isset($types[$type])}{$types[$type]}{else}{$type}{/if}, 
			<?php unset($types[$type]) ?>
		{/f}
		<?php if($term->type!==0) unset($types[0]); ?>
		{* {if!e $types}
			{=$form->select('type',$types,$term->type)->label('Add a type:')}
			{=$form->submit()->container()->addClass('center')}
		{/if} *}
	</div>
	
	{if SearchablesKeyword::existById($term->id)}<div class="mt6">{link 'Go to the keyword','/searchableKeyword/view/'.$term->id}</div>{/if}
</div>

<div id="linkedKeywords" class="clear mt10 block1">
	<h5 class="noclear">{t 'plugin.searchable.LinkedKeywords'}</h5>
	<ul class="compact">
	{f $term->keywords as $keyword}
		<li>{=$keyword->adminLink()}</li>
	{/f}
	</ul>
</div>

/* IF(searchable.keywordTerms.text) */</div>/* /IF */


<div class="mr300 context">
	{=$form->input('term')->attrClass('wp100 biginfo')->container()->addClass('mb10')}
	
	/* IF(searchable.keywordTerms.seo) */
	<? View::element('seo',array('model'=>$term,'form'=>$form)) ?>
	{=$form->submit()->container()->addClass('center')}
	/* /IF */
</div>

/* IF(searchable.keywordTerms.text) */
<div class="clear">
	<h4>Description du terme</h4>
	{=$form->textarea('text')->wp100()}
	{=$form->submit()->container()->addClass('center')}
</div>
/* /IF */
{=$form->end(false)}

<?php HHtml::jsReady('/* IF(searchable.keywordTerms.seo) */_.seo.init($(\'#SearchablesTermTerm\')/*,$(\'#linkedTerms ul\')*/);/* /IF */'
	.'/* IF(searchable.keywordTerms.text) */S.tinymce.init("100%","330px","basicAdvanced",!!_.cms).wordCount().autolink().autoSave().validXHTML()'
		/* IF2(searchable.keywordTerms.seo) */.'.addAttr("onchange_callback",_.seo.tinymceChanged_metaKeywords)'/* /IF2 */
		.'.createForId("SearchablesTermText");/* /IF */'
	.'$("#formTermEdit").ajaxForm(basedir+"searchableTerm/save/'.$term->id.'",false,function(){'
		//.'if($("#SearchablesKeywordDescr").val()==""){alert("Le texte est vide !");return false;}'
	.'});') ?>
<br class="clear"/>
