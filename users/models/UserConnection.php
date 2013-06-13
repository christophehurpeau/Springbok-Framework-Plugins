<?php
/** @TableAlias('uc') @Created */
class UserConnection extends SSqlModel{
	const FORM=0,BASIC=1,COOKIE=2,
		FACEBOOK=10,GOOGLE=11,YAHOO=12,WLIVE=13,OPENID=14;
	
	public
		/** @Pk @AutoIncrement @SqlType('BIGINT(20) unsigned') @NotNull
		 */ $id,
		/** @SqlType('TINYINT(1) unsigned') @NotNull @Comment('0=form,1=basic,2=cookie')
		 * @Enum(0=>'Formulaire',1=>'Rudimentaire',2=>'Cookie',3=>'Après inscription',10=>'Facebook',11=>'Google',12=>'Yahoo',13=>'WindowsLive',14=>'OpenID')
		 */ $type,
		/** @Boolean
		 */ $succeed,
		/** @SqlType('VARCHAR(100)') @Null
		 */ $login,
		/** @SqlType('INT(10) unsigned') @Null
		 */ $connected,
		/** @SqlType('VARCHAR(39)') @NotNull
		 */ $ip;
	
	public function details(){
		return $this->type().' ('.$this->ip.')';
	}
}
