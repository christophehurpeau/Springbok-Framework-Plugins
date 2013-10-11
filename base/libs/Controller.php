<?php
abstract class AController extends Controller{
	public static function beforeDispatch(){
		ACSecure::connect(false);
	}
}
