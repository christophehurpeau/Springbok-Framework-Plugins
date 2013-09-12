<?php
class ACHttpRequest extends CHttpRequest{
	public static function isTrustedProxy($ip){
		return false;
	}
}