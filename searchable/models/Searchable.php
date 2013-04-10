<?php
/** @TableAlias('sb') @Created @Updated @Parent /* IF(searchable_seo) *\/ @Seo /* /IF *\/ @History('SearchableHistory') */
class Searchable extends SSqlModel{
	use BParent,BNormalized,BHistory/* IF(searchable_seo) */,BSlug,BSeo/* /IF */;
	
	const INVALID=0,VALID=1,DELETED=2;
	
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('varchar(300)') @NotNull @MinLenth(3)
		*/ $name,
		/** @SqlType('varchar(500)') @NotNull @NotBindable
		*/ $html_name,
		/** @SqlType('varchar(500)') @NotNull @NotBindable
		*/ $long_name,
		/* IF(searchable.orderField) */
		/** @SqlType('varchar(300)') @NotNull
		* @Index
		*/ $order,
		/* /IF */
		/** @Boolean @Default(true)
		*/ $visible
		/* IF(searchable.statuses) */
		/** @SqlType('tinyint(1) unsigned') @NotNull @Default(1)
		* @Enum(['AConsts','searchableStatuses']) @Index @NotBindable
		*/ $status,
		/* /IF */
		;
	
	public static $beforeSave=array('_setIfName');
	public static $afterSave=array('_reindexIfName');
	
	
	public function htmlAndLongName(){
		$replace=$replacementsHtml=$replacementsLong=array(); $i=1;
		$name=UString::callbackWords($this->name,function($word,$dot) use(&$replace,&$replacementsHtml,&$replacementsLong,&$i){
			$term=SearchablesTerm::QOne()
				->withForce('SearchablesTermAbbreviation',array(0=>array('id'=>'term_id'),
						'with'=>array('SearchablesTerm'=>array('alias'=>'stabbr','fields'=>false,0=>array('abbr_id'=>'id')))))
				->where(array('stabbr.normalized LIKE'=>UString::normalizeWithoutTransliterate($word)));
			if($term!==false){
				$replacementsHtml[]='<abbr title="'.($replacementsLong[]=h($term->term)).'">'.h($word.$dot).'</abbr>';
				return $replace[]='__SEARCHABLE_STRING_TO_REPLACE_'.($i++).'__';
			}
			return $word.$dot;
		});
		
		$hname=h($name);
		$this->html_name=str_replace($replace,$replacementsHtml,$hname);
		$this->long_name=str_replace($replace,$replacementsLong,$hname);
	}

	public function _renormalize(){
		$this->updated=false;
		$this->normalized=$this->normalized();
		$this->htmlAndLongName();
		unset($this->name);
		$this->update('normalized','html_name','long_name');
	}
	
	public function _setIfName(){
		if(!empty($this->name)){
			$this->htmlAndLongName();
			/* IF(searchable.orderField) */
			if(empty($this->order)) $this->order=$this->name;
			/* /IF */
		}
		return true;
	}
	
	private function _reindexIfName($data=null){
		if(!empty($data['name']) || (!empty($this->name) && array_key_exists('visible',$data))){ /* isset will return false if $data['visible']===null */
			$this->reindex();
		}
	}
	
	public function reindex(){
		if($this->isVisible()) SearchableWord::add($this->id,$this->name);
		else SearchableWord::deleteFor($this->id);
	}
	
	public function link($action=null,$more=''){
		//return array('/:controller/:id-:slug(/:action/*)?',_tR(static::LINK_CONTROLLER),sprintf('%03d',$this->id),$this->slug,$action===null?'':_tR($action),$more);
		return array('/:id-:slug/:action/*',$this->id,$this->slug,$action===null?'':_tR($action),$more);
	}
	
	public static function withOptions($options=array()){
		$options['with']=array('Parent');
		$options['orderBy']=array('sb.created'=>'DESC');
		return $options;
	}
	
}