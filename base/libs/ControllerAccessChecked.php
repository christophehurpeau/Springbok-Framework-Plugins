<?php
abstract class AControllerAccessChecked extends AController{
	public static function beforeDispatch(){
		ACSecure::checkAccess();
	}
}
