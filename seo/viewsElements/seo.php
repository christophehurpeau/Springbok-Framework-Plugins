<h5>Métas</h5>
<table class="metas mt10">
	{f array('slug'=>'Url','meta_title'=>'Title','meta_descr'=>'Méta description','meta_keywords'=>'Méta mots-clés') as $k=>$val}
	<?php $auto=$model->$k===($autoResult=$model->{'auto_'.$k}()) ?>
	<tr class="{if $auto}auto{else}manuel{/if}">
		<th class="alignLeft">{$val}</th>
		<td class="state center"><a href="#" onclick="return _.seo.meta(this)" class="italic">{if $auto}Automatique{else}Manuel{/if}</a></td>
		<td>
			<? $form->text($k,array('name'=>false,'label'=>false,'autocomplete'=>'off','id'=>'Seo'.ucFirst($k).'Auto','readonly'=>true,'value'=>$autoResult,'class'=>'wp100 auto'),false) ?>
			<?php $attrs=array('id'=>'Seo'.ucFirst($k),'value'=>$model->$k,'label'=>false,'autocomplete'=>'off','class'=>'wp100 manuel'.($auto?' autoOnLoad':'')); if($auto) $attrs['disabled']=true; echo $form->text($k,$attrs,false) ?>
		</td>
		<td class="smallinfo alignRight" style="width:80px">
			<span class="manuel"><span class="words"></span> mots<br /><span class="chars"></span> caractères</span>
			<span class="auto"><span class="words"></span> mots<br /><span class="chars"></span> caractères</span>
		</td>
	</tr>
	{/f}
</table>