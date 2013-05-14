<?php
exit('TODO : edit yaml files');
define('SRC',dirname(APP).'/src/');
include CORE.'enhancers/Translations.php';
class DevLangsController extends Controller{
	/** */
	function index(){
		render();
	}
	
	/** @ValidParams @NotEmpty('lang') */
	function lang($lang){
		mset($lang);
		$arrayStrings=SpringbokTranslations::findTranslations(SRC);
		$allStrings=$arrayStrings['all']; unset($arrayStrings['all']);
		//debug($arrayStrings);
		//exit;
		
		$db=self::_loadDbLang($lang);
		SpringbokTranslations::checkDb($db);
		
		$translations=$db->doSelectListValue('SELECT s,t FROM t WHERE c=\'a\' AND s NOT LIKE "plugin%"');
		mset($translations,$allStrings,$arrayStrings);
		render();
	}

	/** @ValidParams @NotEmpty('lang') */
	function save($lang, array $data){
		SpringbokTranslations::saveAll($lang,$data);
		
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
		SpringbokTranslations::saveAllSP($lang,$data);
		redirect(['/dev/:controller(/:action/*)?','langs','sp',$lang]);
	}
	
	/** @ValidParams @NotEmpty('lang') */
	function models(string $lang){
		mset($lang);
		
		$all=array();
		$files=SpringbokTranslations::listInfosModels(APP.'models/infos/');
		
		foreach($files as $modelname=>$file){
			$infos=include $file;
			$all[$modelname][]='';
			$all[$modelname][]='New';
			foreach($infos['columns'] as $key=>$v) $all[$modelname][]=$key;
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
		return SpringbokTranslations::loadDbLang(DB::langDir(),$lang);
	}
}