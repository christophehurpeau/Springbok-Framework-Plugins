<?php
/** @TableAlias('ph') */
class PostHistory extends SSqlModel{
	const IMPORT=0,SAVE=1,AUTOSAVE=2;
	public
		/** @Pk @AutoIncrement @SqlType('int(10) unsigned') @NotNull
		*/ $id,
		/** @SqlType('int(10) unsigned') @NotNull
		* @ForeignKey('Post','id')
		*/ $post_id,
		/** @SqlType('text') @NotNull
		*/ $intro,
		/** @SqlType('text') @NotNull
		*/ $text,
		/** @SqlType('tinyint(1) unsigned') @NotNull @Default(1)
		* @Enum(0=>'Import',1=>'Save',2=>'AutoSave')
		*/ $type,
		/** @Pk @SqlType('datetime') @NotNull
		*/ $created;
	
	public static function last($postId){
		return self::QOne()->fields('intro,text')->byPost_id($postId)->orderByCreated();
	}
	
	public static function create(&$post,$type){
		$lastPostHistory=self::last($post->id);
		if($lastPostHistory!==false && $lastPostHistory->intro===$post->intro && $lastPostHistory->text===$post->text) return true;
		return self::QInsert()->set(array(
			'post_id'=>$post->id,
			'intro'=>$post->intro,
			'text'=>$post->text
		));
	}
	
	/*public static function restore($postId,$historyId){
		return Post::QUpdateSelect()
			->with('PostHistory',array('onConditions'=>array('id'=>$historyId)))
			->set('intro','intro')
			->set('text','text')
			->set('updated','created');
	}*/
	
	public function restore(){
		$post=Post::QOne()->fields('meta_descr,intro')->byId($this->post_id);
		$isAutoMetaDescr=$post->auto_meta_descr()===$post->meta_descr;
		
		$post->id=$this->post_id;
		$post->intro=$this->intro;
		$post->text=$this->text;
		if($isAutoMetaDescr){
			$post->meta_descr=$post->auto_meta_descr();
			return $post->update('intro','text','meta_descr');
		}
		return $post->update('intro','text');
	}
}
