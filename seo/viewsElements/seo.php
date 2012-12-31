<h5>Métas</h5>
<table class="metas mt10">
	{f array('slug'=>'Url','meta_title'=>'Title','meta_descr'=>'Méta description','meta_keywords'=>'Méta mots-clés') as $k=>$val}
	<?php $auto=($k==='slug'?$model->$k===$model->{'auto_'.$k}():empty($model->$k)) ?>
	<tr class="{if $auto}auto{else}manuel{/if}">
		<th class="alignLeft w1">{$val}</th>
		<td class="state center w160"><a href="#" onclick="return _.seo.meta(this)" class="italic">{if $auto}Automatique{else}Manuel{/if}</a></td>
		<td>
			<? $form->text($k)->noName()->noLabel()->attr('autocomplete','off')->id('Seo'.ucFirst($k).'Auto')
					->readOnly()->value($model->{'auto_'.$k}())->attrClass('wp100 auto')->noContainer() ?>
			<?php $text=$form->text($k)->id('Seo'.ucFirst($k))->value($model->$k)->noLabel()->attr('autocomplete','off')
									->attrClass('wp100 manuel'.($auto?' autoOnLoad':''));
				if($auto) $text->disabled();
				echo $text->noContainer() ?>
		</td>
		<td class="smallinfo alignRight" style="width:80px">
			<span class="manuel"><span class="words"></span> mots<br /><span class="chars"></span> caractères</span>
			<span class="auto"><span class="words"></span> mots<br /><span class="chars"></span> caractères</span>
		</td>
	</tr>
	{/f}
</table>