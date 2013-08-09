<?php HBreadcrumbs::set(array('Pages'=>'/pages')); $v=new AjaxPageView('Edition page',''); ?>

{=$form=Page::Form()->id('formPageEdit')->attr('novalidate',true)->noDefaultLabel()}
<div class="col fixed right w200">
	<div class="content center">
		{if !($inLocked=in_array($page->id,Config::$cmsLockedIds)) && $page->isPublished()}
			{=$form->select('status',Page::statusesList())}
		{/if}
		{if $inLocked}
			{iconLink 'delete','Supprimer cet article','/pages/delete/'.$page->id,array('confirm'=>'Êtes-vous sûr de vouloir supprimer cette page ?')}
		{/if}
		<p>{if $page->isPublished()}{link 'Page en ligne',$page->link(),array('entry'=>'index','target'=>'_blank','https'=>false)}{/if}</p>
	</div>
	<?php CModule::admin_page_view_col($page,$form) ?>
	<? $form->submit(true)->container()->addClass('center'); ?>
</div>

<div class="col variable r200">
	<? HBreadcrumbs::display(_tC('Home'),$page->id.': '.$page->name) ?>
	<div id="editTabs" class="tabs">
		<ul><li>{iconLink 'page','Page','#editTab1'}</li><li>{iconLink 'pageEdit','Contenu','#editTab2'}</li><li>{iconLink 'time','Historique','/pageHistories/view/'.$id}</li></ul>
		<div id="editTab1" class="clearfix">
			{=$form->input('name')->wp100()}
			
			<div class="sepTop block1">
				<? View::element('seo',array('model'=>$page,'form'=>$form)) ?>
				<? $form->submit(true)->container()->addClass('center'); ?>
			</div>
			
			<br class="clear"/>
		</div>
		<div id="editTab2">
			<div class="alignRight mt10"><a href="#" onclick="S.tinymce.switchtoHtml('PageContent');return false">HTML</a> - <a href="#" onclick="S.tinymce.switchtoVisual('PageContent');return false">Visuel</a></div>
			<? $form->textarea('content') ?>
			
			<? $form->submit(); ?>
		</div>
	</div>
</div>
<? HHtml::jsInline('_.pages.edit('.$id.')') ?>
<? $form->end(false); ?>