<?php
define('SRC',dirname(APP).'/src/');
class DevLangsController extends Controller{
	/** */
	function index(){
		render();
	}
	
	/** @ValidParams @NotEmpty('lang') */
	function lang($lang){
		mset($lang);
		$arrayStrings=array('all'=>array());
		self::_recursiveFiles($projectPath,$arrayStrings);
		$all=array_unique($arrayStrings['all']);
		unset($arrayStrings['all']);
		//debug($arrayStrings);
		//exit;
		
		$db=self::_loadDbLang($lang);
		//$db->doUpdate('CREATE TABLE IF NOT EXISTS t(s NOT NULL,c NOT NULL,t NOT NULL, PRIMARY KEY(s,c)');
		
		
		$dbSchema=new DBSchemaSQLite($db,'t');
		$dbSchema->setModelInfos(array(
			'primaryKeys'=>array('s','c'),
			'columns'=>array(
				's'=>array('type'=>'TEXT','notnull'=>true,'unique'=>false,'default'=>false),
				'c'=>array('type'=>'TEXT','notnull'=>true,'unique'=>false,'default'=>'"a"'),
				't'=>array('type'=>'TEXT','notnull'=>true,'unique'=>false,'default'=>false)
			)
		));
		if(!$dbSchema->tableExist()) $dbSchema->createTable();
		//else $dbSchema->compareTableAndApply();
		
		set('translations',$db->doSelectListValue('SELECT s,t FROM t WHERE c=\'a\' AND s NOT LIKE "plugin%"'));
		
		set_('allStrings',$all);
		set_('arrayStrings',$arrayStrings);
		render();
	}

	/** @ValidParams @NotEmpty('lang') */
	function save($lang, array $data){
		$db=self::_loadDbLang($lang);
		$db->doUpdate('DELETE FROM t WHERE c=\'a\' AND s NOT LIKE "plugin%"');
		$statement=$db->getConnect()->prepare('INSERT INTO t(s,c,t) VALUES (:s,\'a\',:t)');
		if(!empty($data)) foreach($data as $d){
			$statement->bindValue(':s',$d['s']);
			$statement->bindValue(':t',$d['t']);
			$statement->execute();
		}
		
		redirect(['/dev/:controller(/:action/*)?','langs','lang',$lang]);
	}
	
	/** @ValidParams @NotEmpty('lang') */
	function sp(string $lang){
		mset($lang);
		$arrayStrings=array('all'=>array());
		self::_recursiveFiles($projectPath,$arrayStrings,'_t_p',true);
		$all=array_unique($arrayStrings['all']);
		unset($arrayStrings['all']);
		
		
		$db=self::_loadDbLang($lang);
		set('translations',$db->doSelectListRows('SELECT t1.s,t1.t AS singular,t2.t AS plural FROM t t1 LEFT JOIN t t2 ON t1.s=t2.s WHERE t1.c=\'s\' AND t2.c=\'p\''));
		
		set_('allStrings',$all);
		render();
	}
	
	/** @ValidParams @NotEmpty('lang') */
	function sp_save(string $lang, array $data){
		$db=self::_loadDbLang($lang);
		$db->doUpdate('DELETE FROM t WHERE c IN(\'s\',\'p\')');
		$statementSingular=$db->getConnect()->prepare('INSERT INTO t(s,c,t) VALUES (:s,\'s\',:t)');
		$statementPlural=$db->getConnect()->prepare('INSERT INTO t(s,c,t) VALUES (:s,\'p\',:t)');
		foreach($data as $d){
			$statementSingular->bindValue(':s',$d['s']);
			$statementSingular->bindValue(':t',$d['singular']);
			$statementSingular->execute();
			$statementPlural->bindValue(':s',$d['s']);
			$statementPlural->bindValue(':t',$d['plural']);
			$statementPlural->execute();
		}
		redirect(['/dev/:controller(/:action/*)?','langs','sp',$lang]);
	}
	
	/** @ValidParams @NotEmpty('lang') */
	function models(string $lang){
		mset($lang);
		
		$all=array();
		if($dir=opendir(($dirname=APP.'models/infos/'))){
			$files=array();
			while (false !== ($file = readdir($dir)))
				if($file != '.' && $file != '..' && substr($file,-1)!=='_' && !is_dir($filename=$dirname.$file)) $files[$file]=$filename;
			closedir($dir);
			ksort($files);
			
			foreach($files as $modelname=>$file){
				$infos=include $file;
				$all[$modelname][]='';
				$all[$modelname][]='New';
				foreach($infos['columns'] as $key=>$v) $all[$modelname][]=$key;
			}
		}
		
		
		$db=self::_loadDbLang($lang);
		set('translations',$db->doSelectListValue('SELECT s,t FROM t WHERE c=\'f\''));
		
		set_('allStrings',$all);
		render();
	}
	
	/** @ValidParams @NotEmpty('lang','modelname') */
	function modelsSave($lang, string $modelname,array $data){
		$db=self::_loadDbLang($lang);
		$db->doUpdate('DELETE FROM t WHERE c=\'f\' AND s like '.$db->escape($modelname.'%'));
		$statement=$db->prepare('INSERT INTO t(s,c,t) VALUES (:s,\'f\',:t)');
		foreach($data as $s=>$t){
			if($t==='') continue;
			if($s===0) $s='';
			$statement->bindValue(':s',$modelname.':'.$s);
			$statement->bindValue(':t',$t);
			$statement->execute();
		}
		
		renderText('1');
	}
	
	/** @ValidParams @NotEmpty('lang') */
	function plugins($lang){
		mset($lang);
		$enhanceConfig=include SRC.'/config/enhance.php';
		$plugins=array_map(function(&$v){return $v[1];},$enhanceConfig['plugins']);
		
		$db=self::_loadDbLang($lang);
		
		$translations=array();
		foreach($plugins as $plugin){
			$translations[$plugin]=$db->doSelectListValue('SELECT s,t FROM t WHERE c=\'a\' AND s LIKE "plugin.'.$plugin.'%"');
		}
		mset($translations);
		
		render();
	}
	
	
	/** @ValidParams @NotEmpty('lang','pluginName') */
	function pluginSave($lang,$pluginName,array $data){
		$db=self::_loadDbLang($lang);
		$db->doUpdate('DELETE FROM t WHERE c=\'a\' AND s like '.$db->escape('plugin.'.$pluginName.'.%'));
		$statement=$db->prepare('INSERT INTO t(s,c,t) VALUES (:s,\'a\',:t)');
		foreach($data as $s=>$t){
			if($t==='') continue;
			$statement->bindValue(':s',$s);
			$statement->bindValue(':t',$t);
			$statement->execute();
		}
		
		renderText('1');
	}
	
	/** @ValidParams @NotEmpty('lang') */
	function js(string $lang){
		mset($lang);
		$projectPath=SRC.'web/js/';
		$arrayStrings=array('all'=>array());
		self::_recursiveFiles($projectPath,$arrayStrings);
		$all=array_unique($arrayStrings['all']);
		unset($arrayStrings['all']);
		//debug($arrayStrings);
		//exit;
		
		$translations=array();
		if(file_exists($filename=$projectPath.'i18n-'.$lang.'.js')){
			$content=file_get_contents($filename); $matches=array();
			if(preg_match('window.i18n={(.*)};\s*$/Us',$content,$matches)){
			//debug($matches);
			
				foreach(explode("\n",$matches[1]) as $val){
					eval('list($key,$val)=array('.preg_replace('/(\'|\")=(\'|\")/','$1,$2',$val,1).');');
					$translations[$key]=$val;
				}
			}
			/*preg_match('/window.i18n={(.*)\n}/U',$content,$i18nMatches);
			if(!empty($i18nMatches)){
				$i18nMatches[1]=','.$i18nMatches[1];
				preg_match_all('/([^:]*):(.*),\n/Ums',$i18nMatches[1],$matches);
				debug($matches);
				
				foreach($matches[1] as $i=>$k){
					$key='';$val='';
					eval('$key='.$k.';$val='.$matches[2][$i].';');
					$translations[$key]=$val;
				}
			}*/
		}
		set('translations',$translations);
		
		set_('allStrings',$all);
		render();
	}

	/** @ValidParams @NotEmpty('lang') */
	function js_save(int $id, string $lang, array $data){
		$projectPath=SRC.'web/js/';
		
		$content='';
		//if(file_exists($filename=CORE.'includes/js/langs/core-'.$lang.'.js'))
		//	$content="includeCore('langs/core-".$lang."');";
		if(file_exists($filename=CORE.'includes/js/langs/'.$lang.'.js'))
			$content="includeCore('langs/".$lang."');";
		$content.="S.lang='$lang';function _t(string){\nvar t=i18n[string];\nif(t===undefined) return string;\nreturn t;\n}\nwindow.i18n={\n";
		if(!empty($data)) foreach($data as $d)
			$content.="\n".UPhp::exportString($d['s']).':'.UPhp::exportString($d['t']).',';
		$content=substr($content,0,0-1)."\n};";
		
		file_put_contents($projectPath.'i18n-'.$lang.'.js',$content);
		
		redirect(['/dev/:controller(/:action/*)?','langs','js',$lang]);
	}
	
	
	private static function _loadDbLang($lang){
		$projectConfig=include SRC.'config/_'.ENV.'.php';
		return new DBSQLite(false,array( 'file'=>$projectConfig['db']['_lang'].$lang.'.db','flags'=>SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE ));
	}
	
	protected static function _recursiveFiles(&$path,&$arrayStrings,$functionName='_t',$deleteLastParam=false,$pattern=false){
		foreach(new RecursiveDirectoryIterator($path,FilesystemIterator::KEY_AS_PATHNAME | FilesystemIterator::CURRENT_AS_FILEINFO | FilesystemIterator::SKIP_DOTS)
					as $pathname=>$fileInfo){
			if(substr($fileInfo->getFilename(),0,1) == '.') continue;
			if($fileInfo->isDir()) self::_recursiveFiles($pathname,$arrayStrings,$functionName,$deleteLastParam);
			if(!in_array(($ext=substr($fileInfo->getFilename(),-3)),array('.js','php')) || substr($fileInfo->getFilename(),0,4)=='i18n') continue;
			$matches=array(); preg_match_all($pattern?$pattern:'/(?:\b'.$functionName.'\((.+)\)'.($ext==='php'?'|\{'.substr($functionName,1).'\s+([^}]+)\s*\}':'').')/Um',file_get_contents($pathname),$matches);
			if(!empty($matches[1])){
				foreach($matches[1] as $key=>$value)
					if(empty($matches[1][$key])) $matches[1][$key]=$matches[2][$key];
				unset($matches[2]);
				
				$matches=array_map(function($v) use(&$deleteLastParam){
					$string=substr($v,1);
					if($deleteLastParam) $string=substr($string,0,strrpos($string,','));
					return stripslashes(substr($string,0,-1));
				},$matches[1]);
				foreach($matches as $keyM=>$match) if(substr($match,0,7)==='plugin.') unset($matches[$keyM]);
				$arrayStrings['all']=array_merge($arrayStrings['all'],$matches);
				$arrayStrings[$pathname]=$matches;
			}
		}
	}
}