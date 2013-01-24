<?php
class VLinkedPosts extends SViewElement{
	public static function vars($termId,$proximityMax=5){
		$where=array('skt.term_id'=>$termId,'skt.proximity <='=>$proximityMax);
		//if($removeCurrent!==false) $where['term_id !=']=$termId;
		$posts=Post::QListAll()->addField('excerpt')->limit(/* VALUE(blog.VPostsLatest.size) */)
			->withForce('SearchablesKeywordTerm')
			->addCondition('skt.term_id',$termId)->addCondition('skt.proximity <=',$proximityMax)
			->groupBy('id')
			->orderBy(array('YEARWEEK(sb.created)'=>'DESC','MIN(skt.proximity)','sb.created'=>'DESC'));
		foreach($posts as $post)
			$post->excerpt=UHtml::transformInternalLinks($post->excerpt,Config::$internalLinks,'index',/* VALUE(blog.VPostsLatest.fullUrls) *//* HIDE */false/* /HIDE */);
		return array(
			'posts'=>$posts
		);
	}
}