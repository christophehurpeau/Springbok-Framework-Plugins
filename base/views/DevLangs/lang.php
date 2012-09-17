<?php new AjaxContentView($lang,'Dev/default'); ?>

<div><?php $form=HForm::create(NULL,array('action'=>['/dev/:controller(/:action/*)?','langs','save',$lang));
	$i=0;
	foreach($allStrings as $filename=>$string){
		echo $form->hidden('data['.$i.'][s]',$string);
		echo $form->input('data['.$i.'][t]',array('label'=>$string,'id'=>'data_'.$i,'value'=>isset($translations[$string])?$translations[$string]:''));
		$i++;
	}
	$form->end(_t('Save'));
?></div>