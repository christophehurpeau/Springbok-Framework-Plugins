<?php new View(_t('plugin.contactForm.Contact')); HMeta::canonical('/site/contact'); HMeta::noindex_follow() ?>
<h1>{t 'plugin.contactForm.Contact'}</h1>
<p><? nl2br(h(_t('plugin.contactForm.details'))) ?></p>
<p class="italic">{t 'plugin.contactForm.allFieldsRequired'}</p>

{=$form=HForm::Post()->action('/site/contact_submit')->setClass('mediumLabel')}
{=$form->input('name')->label(_t('plugin.contactForm.Name:'))->size(60)}
{=$form->input('email')->label(_t('plugin.contactForm.Email:'))->size(60)}
{=$form->input('subject')->label(_t('plugin.contactForm.Subject:'))->size(60)}
{=$form->textarea('content')->label(_t('plugin.contactForm.Content:'))}
<p><img id="captchaImg" class="vaMiddle" src="<? HHtml::url('/site/captchaImage') ?>"/>
<a href="javascript:void(0);" onclick="javascript:document.images.captchaImg.src='<? HHtml::url('/site/captchaImage') ?>?' + Math.round(Math.random(0)*1000)+1">{t 'plugin.contactForm.ChangeCaptcha'}</a></p>
{=$form->input('captcha')->label(_t('plugin.contactForm.Captcha:'))->size(60)}
{=$form->end(_t('plugin.contactForm.Send'))}
