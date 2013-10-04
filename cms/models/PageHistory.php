<?php
/** @TableAlias('ph') */
class PageHistory extends SSqlModel{
	const IMPORT=0,SAVE=1,AUTOSAVE=2;
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Page','id')
		*/ $page_id,
		/** @SqlType('text') @NotNull
		*/ $content,
		/** @SqlType('tinyint(1) unsigned') @NotNull @Default(1)
		* @Enum(0=>'Import',1=>'Save',2=>'AutoSave')
		*/ $type,
		/** @Pk @SqlType('datetime') @NotNull
		*/ $created;
	
	public static function last($pageId){
		return self::QOne()->fields('content')->byPage_id($pageId)->orderByCreated()->fetch();
	}
	
	public static function create($page,$type){
		$lastPageHistory=self::last($page->id);
		if($lastPageHistory!==false && $lastPageHistory->content===$page->content) return true;
		return self::QInsert()->set(array(
			'page_id'=>$page->id,
			'content'=>$page->content
		))->execute();
	}
	
	/*public static function restore($postId,$historyId){
		return Page::QUpdateSelect()
			->with('PageHistory',array('onConditions'=>array('id'=>$historyId)))
			->set('excerpt','excerpt')
			->set('content','content')
			->set('updated','created');
	}*/
	
	public function restore(){
		// force using model : afterSave()...
		$page=new Page;
		$page->id=$this->page_id;
		$page->content=$this->content;
		return $post->update('content');
	}
}
