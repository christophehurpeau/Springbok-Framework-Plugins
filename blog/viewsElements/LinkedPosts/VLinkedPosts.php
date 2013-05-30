<?php
class VLinkedPosts extends SViewElement{
	public static function vars($termId,$proximityMax=5,$ifEmptySetLatests=false){
		$where=array('skt.term_id'=>$termId,'skt.proximity <='=>$proximityMax);
		//if($removeCurrent!==false) $where['term_id !=']=$termId;
		$posts=Post::QListAll()->addField('excerpt')->limit(/*#val blog.VPostsLatest.size */)
			->withForce('SearchablesKeywordTerm')
			->addCondition('skt.term_id',$termId)->addCondition('skt.proximity <=',$proximityMax)
			->groupBy('id')
			->orderBy(array('YEARWEEK(sb.created)'=>'DESC','MIN(skt.proximity)','sb.created'=>'DESC'));
		if($ifEmptySetLatests!==false && empty($posts))
			{ $vars=VPostsLatest::vars(); $vars['latest']=true; return $vars; }
		foreach($posts as $post)
			$post->excerpt=UHtml::transformInternalLinks($post->excerpt,Config::$internalLinks,'index',/*#val blog.VPostsLatest.fullUrls */false);
		return array(
			'posts'=>$posts,
			'latest'=>false,
		);
	}
}