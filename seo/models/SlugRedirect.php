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
		if(!self::QInsert()->ignore()->set(array('model_name'=>$modelName,'old_slug'=>$oldSlug,'new_slug'=>$newSlug,'direct'=>true))->execute())
			self::QUpdateOneField('direct',true)->where(array('model_name'=>$modelName,'old_slug'=>$oldSlug,'new_slug'=>$newSlug))->execute();
		if(self::QUpdateOneField('direct',false)->byNew_slug($oldSlug)->execute())
			self::QInsertSelect()->ignore()->query(self::QAll()->setFields(array('model_name','old_slug','('.UPhp::exportString($newSlug).')','("")','NOW()'))
							->where(array('new_slug'=>$oldSlug,'model_name'=>$modelName)))->execute();
	}
	/*
	public static function slugAdded($modelName,$slug){
		self::QUpdateOneField('direct',false)->where(array('model_name'=>$modelName,'old_slug'=>$slug));
	}*/
	
	
	public static function get($modelName,$oldSlug){
		return self::QValue()->field('new_slug')->where(array('old_slug LIKE'=>$oldSlug,'direct'=>true))->fetch();
	}
}