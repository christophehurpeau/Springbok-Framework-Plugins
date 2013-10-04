<?php
/** @Check('ACSecureAdmin') */
class GuestsStatisticsController extends Controller{
	/**
	*/ function index($scriptname){
		if($scriptname===null) $scriptname='index';
		mset($scriptname);
		set('mobile',GuestConf::QRow()->setFields(array('SUM(IF(is_mobile IS NULL,0,1))'=>'mobile','SUM(IF(is_mobile IS NULL,1,0))'=>'others'))->fetch());
		set('scriptnames',GuestRequest::QValues()->field('DISTINCT scriptname')->orderBy('scriptname')->fetch());
		set('most_requests',GuestRequest::QRows()->setFields(array('resource','COUNT(1)'=>'count'))->byScriptname($scriptname)->groupBy('resource')->orderBy(array('count'=>'DESC'))->limit(15)->fetch());
		render();
	}
}