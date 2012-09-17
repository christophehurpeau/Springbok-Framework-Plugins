<?php new AjaxContentView($lang,'Dev/default'); ?>

<ul class="compact">
{f $allStrings as $modelName=>$modelStrings}
	<li>{link $modelName,'#',array('onclick'=>'$(this).parent().find("> div").slideToggle();return false;')}
	<div class="hidden">
		<?php $form=HForm::create(NULL,array('id'=>'FormLangsModels_'.$modelName,'action'=>'#')); $i=0; ?>
		<ul>
		{f $modelStrings as $filename=>$string}
			<li>
				{=$form->input('data['.$string.']',array('label'=>empty($string)?'Table name':$string,'id'=>'data_'.$i++,'value'=>isset($translations[$modelName.':'.$string])?$translations[$modelName.':'.$string]:''))}
			</li>
		{/f}
		</ul>
		{=$form->end()}
	</div>
{/f}
</ul>

{jsReady}
{f $allStrings as $modelName=>$modelStrings}
	$("#FormLangsModels_{$modelName}").ajaxForm("<? HHtml::url(['/dev/:controller(/:action/*)?','devLLangs','modelsSave',$lang]) ?>/{$modelName}");
{/f}
{/jsReady}