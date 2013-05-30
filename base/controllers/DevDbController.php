<?php /*#if DEV */
Controller::$defaultLayout='Dev/db';
class DevDbController extends AController{
	
	/** */
	function beforeRender(){
		$models=array();
		foreach(new FilesystemIterator(APP.'models/infos') as $file){
			$modelName=$file->getFilename();
			$models[$modelName::$__dbName][]=$modelName;
		}
		ksort($models);
		self::setForLayout('models',$models);
		return true;
	}
	
	/** */
	function index(){
		render();
	}
	
	/** @NotEmpty('modelName') */
	function model($modelName){
		self::beforeRender();
		$modelName::Table()->noAutoRelations()
			->allowFilters()
			->paginate()
			->addAction(array('view',HHtml::url(array('/dev/:controller(/:action/*)?','db','view','/'.$modelName))))
			->render($modelName);
	}
	
	/** @NotEmpty('modelName','pk') */
	function view($modelName,$pk){
		$table=$modelName::TableOne()->where(array($modelName::_getPkName()=>$pk))->noAutoRelations()->notFoundIfFalse();
		$row=$table->getResult();
		
		//TODO : use models, including for the relations, this will be faster.
		
		$dbSchema=DBSchema::get($db=$modelName::$__modelDb,$modelName::_fullTableName());
		$pks=$dbSchema->getPrimaryKeys();
		$bT=$dbSchema->getForeignKeys();
		$hM=$dbSchema->getHasManyForeignKeys();
		
		$belongsTo=$hasMany=array();
		
		foreach($bT as $fk){
			$belongsTo[$fk['referenced_table']]=$db->doSelectRow('SELECT * FROM '
				.$db->formatTable($fk['referenced_table']).' WHERE '.$db->formatField($fk['referenced_column']).'='.$db->escape($row[$fk['column']]));
		}
		foreach($hM as $fk){
			$query='FROM '.$db->formatTable($fk['tableName']).' WHERE '.$db->formatField($fk['column']).'='.$db->escape($row[$fk['referenced_column']]);
			$count=$db->doSelectValue('SELECT count(*) '.$query);
			$results=$count==0 || $count > 8 ? null : $db->doSelectRows('SELECT * '.$query); 
			$hasMany[$fk['tableName']]=array('count'=>$count,'results'=>$results);
		}
		
		mset($table,$belongsTo,$hasMany);
		render();
	}
}
/*#/if */