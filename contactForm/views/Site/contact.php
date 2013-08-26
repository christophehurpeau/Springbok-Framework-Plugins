<?php new View(_t('plugin.contactForm.Contact')); HMeta::canonical('/site/contact'); HMeta::noindex_follow() ?>
<h1>{t 'plugin.contactForm.Contact'}</h1>
<p><? nl2br(h(_t('plugin.contactForm.details'))) ?></p>
<p class="italic">{t 'plugin.contactForm.allFieldsRequired'}</p>

<?php $form=HForm::create(NULL,array('action'=>'/site/contact_submit','class'=>'mediumLabel')) ?>
<? $form->input('name',array('label'=>_t('plugin.contactForm.Name:'),'size'=>60)) ?>
<? $form->input('email',array('label'=>_t('plugin.contactForm.Email:'),'size'=>60)) ?>
<? $form->input('subject',array('label'=>_t('plugin.contactForm.Subject:'),'size'=>60)) ?>
<? $form->textarea('content',array('label'=>_t('plugin.contactForm.Content:'))) ?>
<p><img id="captchaImg" class="vaMiddle" src="<? HHtml::url('/site/captchaImage') ?>"/>
<a href="javascript:void(0);" onclick="javascript:document.images.captchaImg.src='<? HHtml::url('/site/captchaImage') ?>?' + Math.round(Math.random(0)*1000)+1">{t 'plugin.contactForm.ChangeCaptcha'}</a></p>
<? $form->input('captcha',array('label'=>_t('plugin.contactForm.Captcha:'),'size'=>30)) ?><br />
<? $form->end(_t('plugin.contactForm.Send')) ?>
