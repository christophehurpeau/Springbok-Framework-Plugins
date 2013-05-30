<?php
/** @TableAlias('pg') @DisplayField('name') @Child('Searchable','created,updated') @Created @Updated @UniqueSlug */
class Page extends Searchable{
	use BChild,BSlug,BSlugRedirectable,BSeo;
	
	const DRAFT=1,PUBLISHED=2,DELETED=4;
		
	public
		/** @SqlType('varchar(180)') @NotNull @MinLenth(3)
		*/ $name,
		/** @SqlType('int(10) unsigned') @NotNull
		*  @ForeignKey('User','id')
		*/ $author_id,
		/*#if cms.multisite*/
		/** @Index @SqlType('tinyint(3) unsigned') @NotNull
		*/ $site,
		/*#/if*/
		/** @SqlType('text') @NotNull
		*/ $content,
		/** @SqlType('tinyint(1)') @NotNull
		* @Enum(1=>'Draft',2=>'Published',4=>'Deleted')
		*/ $status,
		/** @SqlType('datetime') @Null @Default(NULL)
		* @NotBindable
		*/ $published;
	
	
	public static function afterCreateTable(){
	}
	
	public static $afterSave=array('destroyVElement');
	
	
	public static function findOneById($id){
		return self::QOne()->fields('id,slug')->byId($id);
	}
	
	public function destroyVElement(){
		VPage::destroy($this->id);
		return true;
	}
	
	public function auto_meta_descr(){ return trim(preg_replace('/[\s\r\n]+/',' ',str_replace('&nbsp;',' ',html_entity_decode(strip_tags($this->content),ENT_QUOTES,'UTF-8')))); }
	public function auto_meta_keywords(){
		return '';
	}
	
	public function save(){
		if($this->status===self::PUBLISHED && !self::existByIdAndStatus($this->id,self::PUBLISHED)) $this->published=array('NOW()');
		$res=$this->update();
		$this->visible=$this->status===self::PUBLISHED;
		if(isset(static::$__modelInfos['columns']['p_id']) && empty($this->p_id)) $this->p_id=Page::findValueP_idById($this->id);
		$resP=$this->updateParent();
		if($res && $this->status===self::PUBLISHED)
			self::onModified($this->id);
		return $res;
	}
	
	public static function onModified($pageId,$delete=false){
		$delete ? VPage::destroy($pageId) : VPage::generate($pageId);
		ACSitemapPages::generate();
	}
	
	
	public function link($action=null,$more=''){
		return $this->id===1?'/':array('/:slug',$this->slug);
	}
	
	public function isPublished(){return $this->status!==self::DRAFT;}
	
	public function toJSON_autocomplete(){
		return json_encode(array('id'=>$this->id,'value'=>$this->name,'url'=>HHtml::url($this->link(),'index',true)));
	}
	
	public function toJSON_autocompleteSimple(){
		return json_encode(array('id'=>$this->id,'name'=>$this->name));
	}
	public static function internalLink($id){
		$page=new Page; $page->id=$id;
		$page->slug=Page::QValue()->field('slug')->addCondition('id',$id);
		return $page->link();
	}
}