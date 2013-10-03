<?php /*#if DEV */
Controller::$defaultLayout='Dev/db';
class ModelCRUD extends CRUD{
	public static $modelName;
	protected static function redirection($pk){
		Controller::redirect(array('/dev/:controller(/:action/*)?','db','view','/'.self::$modelName.'/'.$pk));
	}
}

class DevDbController extends AController{
	
	/** */
	static function beforeRender(){
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
	static function index(){
		render();
	}
	
	/** @NotEmpty('modelName') */
	static function model($modelName){
		self::beforeRender();
		$modelName::Table()->noAutoRelations()
			->allowFilters()
			->paginate()
			->addAction(array('view',CRoute::getArrayLink('index',array('/dev/:controller(/:action/*)?','db','view','/'.$modelName)),'title'=>_tC('View')))
			->addAction(array('edit',CRoute::getArrayLink('index',array('/dev/:controller(/:action/*)?','db','edit','/'.$modelName)),'title'=>_tC('Edit')))
			->render($modelName);
	}
	
	/** @NotEmpty('modelName','pk') */
	static function view($modelName,$pk){
		$table=$modelName::TableOne()->where(array($modelName::_getPkName()=>$pk))->noAutoRelations()->mustFetch()
			->addAction(array('edit',CRoute::getArrayLink('index',array('/dev/:controller(/:action/*)?','db','edit','/'.$modelName)),'title'=>_tC('Edit')));
		
		$relations = array();
		foreach($modelName::$_relations as $relName => $relOptions){
			if(isset($relOptions['modelName']))
				$relations[$relName] = $table->rel($relName,array('dataName'=>'relation_'.$relName,'fields'=>null,'fieldsInModel'=>false))
						->noAutoRelations()->paginate()
						->addAction(array('view',CRoute::getArrayLink('index',array('/dev/:controller(/:action/*)?','db','view','/'.$relOptions['modelName'])),'title'=>_tC('View')));
		}
		
		mset($table,$relations);
		render();
		exit;
		
		//TODO : use models, including for the relations, this will be faster.
		$row=$table->getResult();
		
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

	}
	
	/** @NotEmpty('modelName','pk') */
	static function edit($modelName,$pk){
		self::beforeRender();
		ModelCRUD::edit(ModelCRUD::$modelName=$modelName,$pk);
	}
}
/*#/if */