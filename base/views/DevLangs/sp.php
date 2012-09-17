<?php new AjaxContentView($lang,'Dev/default'); ?>

<div><?php $form=HForm::create(NULL,array('action'=>['/dev/:controller(/:action/*)?','langs','lang_sp_save',$lang]));
	$i=0;
	foreach($allStrings as $filename=>$string){
		echo $form->hidden('data['.$i.'][s]',$string);
		
		echo '<div class="input">';
		echo HHtml::tag('label',array('for'=>'data_s'.$i),$string);
		echo $form->input('data['.$i.'][singular]',array('label'=>false,'id'=>'data_s'.$i,'value'=>isset($translations[$string]['singular'])?$translations[$string]['singular']:''),false);
		echo $form->input('data['.$i.'][plural]',array('label'=>false,'id'=>'data_p'.$i,'value'=>isset($translations[$string]['plural'])?$translations[$string]['plural']:''),false);
		echo '</div>';
		$i++;
	}
	$form->end(_t('Save'));
?></div>
