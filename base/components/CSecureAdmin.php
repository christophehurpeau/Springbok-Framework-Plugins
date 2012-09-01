<?php
class ACSecureAdmin extends CSecureAdmin{
	public static function checkAccess($params=User::ADMIN){
		return parent::checkAccess($params);
	}
}