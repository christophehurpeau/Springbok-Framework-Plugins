<div id="linkedTerms" class="clear mt10 block1">
	<h5 class="noclear">{t 'plugin.searchable.LinkedTerms'}</h5>
	<? HHtml::ajaxCRDInputAutocomplete('/searchableKeyword',$keyword->terms,
			array('js'=>'{allowNew:searchable.createTerm,add:searchable.addTerm,actions:{edit:searchable.editTerm},url:"/'.$keyword->id.'"}',
				'actions'=>['edit'],'modelFunctionName'=>'adminLink','escape'=>false)) ?>
</div>