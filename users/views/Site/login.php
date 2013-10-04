<?php new AjaxPageView(_tC('Sign in')) ?>

<? CSession::flash('user/login') ?>
<div class="clear mt20">
{=$form=User::Form()->setClass('w400 centered big')->action('/site/login')}
<h2>{tC 'Sign in'}</h2>
{=$form->fieldsetStart()}
<br/>
{=$form->input('email')}
{=$form->input('pwd')}
{=$form->submit(_tC('Sign in'))->container()->setClass('center')}
/*echo '<br class="clear">';
echo HHtml::tag('div',array('class'=>'center','style'=>'float:none'),HHtml::link(_tC('Password lost ?'),'/site/lostPassword'),false);*/
{=$form->end(false)}
</div>