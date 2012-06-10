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
		*/ $excerpt,
		/** @SqlType('text') @NotNull
		*/ $content,
		/** @SqlType('tinyint(1) unsigned') @NotNull @Default(1)
		* @Enum(0=>'Import',1=>'Save',2=>'AutoSave')
		*/ $type,
		/** @Pk @SqlType('datetime') @NotNull
		*/ $created;
	
	public static function last($postId){
		return self::QOne()->fields('excerpt,content')->byPost_id($postId)->orderByCreated();
	}
	
	public static function create(&$post,$type){
		$lastPostHistory=self::last($post->id);
		if($lastPostHistory!==false && $lastPostHistory->excerpt===$post->excerpt && $lastPostHistory->content===$post->content) return true;
		return self::QInsert()->set(array(
			'post_id'=>$post->id,
			'excerpt'=>$post->excerpt,
			'content'=>$post->content
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
		$post=Post::QOne()->fields('meta_descr,excerpt')->byId($this->post_id);
		$isAutoMetaDescr=$post->auto_meta_descr()===$post->meta_descr;
		
		$post->id=$this->post_id;
		$post->excerpt=$this->excerpt;
		$post->content=$this->content;
		if($isAutoMetaDescr){
			$post->meta_descr=$post->auto_meta_descr();
			return $post->update('excerpt','content','meta_descr');
		}
		return $post->update('excerpt','content');
	}
}
