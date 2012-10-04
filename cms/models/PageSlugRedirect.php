<?php
/** @TableAlias('psr') */
class PageSlugRedirect extends SSqlModel{
	public
		/** @Pk @SqlType('varchar(100)') @NotNull
		*/ $old_slug,
		/** @Pk @SqlType('varchar(100)') @NotNull
		*/ $new_slug,
		/** @Boolean @Default(true)
		*/ $direct,
		/** @SqlType('datetime') @NotNull
		*/ $created;
	
	public static function add($oldSlug,$newSlug){
		$psr=new self;
		$psr->old_slug=$oldSlug;
		$psr->new_slug=$newSlug;
		$psr->direct=true;
		$psr->insertIgnore();
		if(self::QUpdateOneField('direct',false)->byNew_slug($oldSlug))
			self::QInsertSelect()->query(self::QAll()->setFields(array('old_slug','('.UPhp::exportString($newSlug).')','("")','NOW()'))->byNew_slug($oldSlug));
	}
	
	
	public static function get($oldSlug){
		return self::QValue()->field('new_slug')->where(array('old_slug LIKE'=>$oldSlug,'direct'=>true));
	}
}