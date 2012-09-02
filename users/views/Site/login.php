<?php new PageView(_tC('Sign in')) ?>

<? CSession::flash('user/login') ?>
<div id="page">
<div class="clear mt20">
<?php $form=HForm::create('User',array('class'=>'w400 centered big','action'=>'/site/login'));
echo '<h2>'._tC('Sign in').'</h2>';
echo $form->fieldsetStart();
echo '<br/>';
echo $form->input('email');
echo $form->input('pwd');
echo $form->submit(_tC('Sign in'),array(),array('class'=>'center'));
/*echo '<br class="clear">';
echo HHtml::tag('div',array('class'=>'center','style'=>'float:none'),HHtml::link(_tC('Password lost ?'),'/site/lostPassword'),false);*/
$form->end(false); ?>
</div>
</div>