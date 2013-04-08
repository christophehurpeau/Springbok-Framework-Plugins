<?php
/** @TableName('searchable_history') @TableAlias('sh') @Created */
class SearchableHistory extends SSqlModel{
	/* [01][0-9] RESERVED */
	const 
		CREATED=01,UPDATED=02,DELETED=03,STATUS_CHANGED=04,
		MERGED=05,MERGED_TO=06
	;
	
	
	public
		/** @Pk @AutoIncrement @SqlType('BIGINT(20) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Searchable','id')
		*/ $searchable_id,
		/** @SqlType('int(10) unsigned') @Null
		* @ForeignKey('User','id')
		*/ $user_id,
		/* IF(searchableHistory.source) */
		/** @SqlType('tinyint(2) unsigned') @NotNull
		* @Enum(['AConsts','sources'])
		*/ $source,
		/* /IF */
		/** @SqlType('tinyint(2) unsigned') @NotNull @Index
		*/ $type,
		/** @SqlType('int(10) unsigned') @Null
		*/ $rel_id;
	
	
	
	
	public static $belongsToType=array(
		'type'=>array(
			'dataName'=>'details',
			'types'=>array(
				
			),
			'relations'=>array(
				
			)
		)
	);
	
	
	
	
	public static function add($searchableId,$type,$relId=null,$userId=true/* IF(searchableHistory.source) */,$source=AConsts::DEFAULT_SOURCE/* /IF */){
		if($userId===true) $userId=CSecure::connected();
		$oh=new self;
		$oh->searchable_id=$searchableId;
		$oh->user_id=$userId;
		$oh->type=$type;
		/* IF(searchableHistory.source) */ $oh->source=$source; /* /IF */
		if($relId !== null) $oh->rel_id=$relId;
		$oh->insert();
	}
}