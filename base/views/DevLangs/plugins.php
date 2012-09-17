<?php new AjaxContentView($lang,'Dev/default'); ?>

<ul class="compact">
{f $translations as $pluginName=>$pluginStrings}
	<li>{link $pluginName,'#',array('onclick'=>'$(this).parent().find("> div").slideToggle();return false;')}
	<div class="hidden">
		<?php $form=HForm::create(NULL,array('id'=>'FormLangsPlugin_'.$pluginName,'action'=>'#')); $i=0; ?>
		<ul>
		{f $pluginStrings as $s=>$t}
			<li>
				{=$form->input('data['.$s.']',array('label'=>$s,'id'=>'data_'.$i++,'value'=>$t))}
			</li>
		{/f}
		</ul>
		{=$form->end()}
	</div>
{/f}
</ul>
{jsReady}
{f $translations as $pluginName=>$pluginStrings}
	$("#FormLangsPlugin_{$pluginName}").ajaxForm(basedir+"dev/devLangs/pluginSave/{=$project->id}/{$lang}/{$pluginName}");
{/f}
{/jsReady}