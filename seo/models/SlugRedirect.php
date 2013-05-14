<?php
/** @TableAlias('psr') */
class SlugRedirect extends SSqlModel{
	public
		/** @Pk @SqlType('varchar(100)') @NotNull
		*/ $model_name,
		/** @Pk @SqlType('varchar(100)') @NotNull
		*/ $old_slug,
		/** @Pk @SqlType('varchar(100)') @NotNull
		*/ $new_slug,
		/** @Boolean @Default(true)
		*/ $direct,
		/** @SqlType('datetime') @NotNull
		*/ $created;
	
	public static function add($modelName,$oldSlug,$newSlug){
		self::QInsert()->ignore()->set(array('model_name'=>$modelName,'old_slug'=>$oldSlug,'new_slug'=>$newSlug,'direct'=>true));
		if(self::QUpdateOneField('direct',false)->byNew_slug($oldSlug))
			self::QInsertSelect()->query(self::QAll()->setFields(array('old_slug','('.UPhp::exportString($newSlug).')','("")','NOW()'))->byNew_slug($oldSlug));
	}
	
	public static function slugAdded($modelName,$slug){
		self::QUpdateOneField('direct',false)->where(array('model_name'=>$modelName,'old_slug'=>$slug));
	}
	
	
	public static function get($modelName,$oldSlug){
		return self::QValue()->field('new_slug')->where(array('old_slug LIKE'=>$oldSlug,'direct'=>true));
	}
}