<?php
class ACAcl extends CAcl{
	public static function checkAccess($permission){
		return CSecure::isConnected() ? true : false;
	}
}