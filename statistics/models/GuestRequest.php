<?php
/** @TableAlias('gur') */
class GuestRequest extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @Index
		*/ $guest_id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @Index
		*/ $guest_conf_id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @Index
		*/ $guest_ip_id,
		/** @SqlType('varchar(30)') @NotNull
		* @Index
		*/ $scriptname,
		/** @SqlType('varchar(300)') @NotNull
		*/ $resource,
		/** @SqlType('varchar(300)') @Null
		*/ $referer,
		/** @SqlType('varchar(300)') @Null
		*/ $referer_domain,
		/** @SqlType('varchar(300)') @Null
		*/ $search_terms,
		/** @SqlType('DATETIME') @NotNull
		*/ $created;
	
	public static function create($gid,$gcid,$giid){
		$data=array('guest_id'=>$gid,'scriptname'=>Springbok::$scriptname,'guest_conf_id'=>$gcid,'guest_ip_id'=>$giid);
		if(isset( $_SERVER['REQUEST_URI'])) $data['resource']=$_SERVER['REQUEST_URI'];
		elseif(isset($_SERVER['SCRIPT_NAME'])){
			$data['resource']=$_SERVER['SCRIPT_NAME'];
			if(isset( $_SERVER['QUERY_STRING'])) $data['resource'] .='?'.$_SERVER['QUERY_STRING'];
		}elseif(isset($_SERVER['PHP_SELF'])){
			$data['resource']=$_SERVER['PHP_SELF'];
			if(isset($_SERVER['QUERY_STRING'])) $data['resource'].='?'.$_SERVER['QUERY_STRING'];
		}
		$ref=CHttpRequest::parseReferer();
		if($ref!==false){
			$data['referer']=&$ref['referer'];
			if(!empty($ref['searchTerms'])) $data['search_terms']=&$ref['searchTerms'];
		}
		return self::QInsert()->data($data);
	}
}