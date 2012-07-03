<?php HBreadcrumbs::set(array('Pages'=>'/pages')); $v=new AjaxBreadcrumbsPageView('Edition page','mr200'); ?>

<?php $form=HForm::create('Page',array('id'=>'formPageEdit','novalidate'=>true),'div',false); ?>
<div class="fixed right w200">
	<div class="content center">
		{=$form->select('status',Page::statusesList())}
		{iconLink 'delete','Supprimer cet article','/pages/delete/'.$page->id,array('confirm'=>'Êtes-vous sûr de vouloir supprimer cette page ?')}
	</div>
	<? $form->submit(true,array(),array('class'=>'submit center')); ?>
</div>

<div class="variable padding">
	<div id="editTabs" class="tabs">
		<ul><li>{iconLink 'page','Page','#editTab1'}</li><li>{iconLink 'pageEdit','Contenu','#editTab2'}</li><li>{iconLink 'time','Historique','/pageHistories/view/'.$id}</li></ul>
		<div id="editTab1" class="clearfix">
			{=$form->input('name',array('class'=>'wp100'))}
			
			<div class="sepTop content block4">
				<? View::element('seo',array('model'=>$page,'form'=>$form)) ?>
				<? $form->submit(true,array(),array('class'=>'submit center')); ?>
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