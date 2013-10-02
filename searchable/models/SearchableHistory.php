<?php
/** @TableName('searchable_history') @TableAlias('sh') @Created */
class SearchableHistory extends SSqlModel{
	public
		/** @Pk @AutoIncrement @SqlType('BIGINT(20) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Searchable','id','onDelete'=>'CASCADE')
		*/ $searchable_id,
		/** @SqlType('int(10) unsigned') @Null
		* @ForeignKey('User','id')
		*/ $user_id,
		/*#if searchableHistory.source*/
		/** @SqlType('tinyint(2) unsigned') @NotNull
		* @Enum(['AConsts','sources'])
		*/ $source,
		/*#/if*/
		/** @SqlType('tinyint(2) unsigned') @NotNull @Index
		*/ $type,
		/** @SqlType('int(10) unsigned') @Null
		*/ $rel_id;
	
	
	/* [01][0-9] RESERVED */
	public static $history=array(
		01=>CREATED,
		02=>UPDATED,
		03=>DELETED,
		04=>STATUS_CHANGED,
		05=>MERGED,
		06=>MERGED_TO,
	);
	
	public static function add($searchableId,$type,$relId=null,$userId=true/*#if searchableHistory.source*/,$source=true/*#/if*/,$date=null){
		if($userId===true) $userId=CSecure::connected();
		$h=new self;
		$h->searchable_id=$searchableId;
		if($userId!==null) $h->user_id=$userId;
		$h->type=$type;
		/*#if searchableHistory.source*/ $h->source=$source===true||$source===null ? AConsts::DEFAULT_SOURCE : $source; /*#/if*/
		if($relId !== null) $h->rel_id=$relId;
		if($date!==null) $h->created=$date;
		$h->insert();
	}
	
	
	public function hasDetails(){
		return isset($this->details);
	}
	public function hasMoreDetails(){
		return method_exists($this->details,'moreDetails');
	}
	public function moreDetails(){
		return $this->details->moreDetails();
	}
}