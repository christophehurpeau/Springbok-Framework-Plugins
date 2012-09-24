<?php
class SiteController extends AController{
	/** */
	function contact(){
		render();
	}
	
	
	/** @NotEmtpy('name','email','subject','content','captcha')
	* email > @Email */
	function contact_submit($name,$email,$subject,$content,$captcha){
		if(!CValidation::hasErrors() && CCaptcha::check()){
			$mailer=CMail::get();
			$mailer->AddAddress(Config::$adminEmail);
			$mailer->Subject='['.Config::$projectName.'] Contact de: '.$email;
			$mailer->Body='<p><b>Sujet : '.$subject.'</b></p><p>'.h($content).'</p>';
			$mailer->AddReplyTo($email);
			$mailer->Send();
			render();
		}else render('contact');
	}
}