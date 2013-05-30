<?php $v=new View($metas['title'],'blog');
HMeta::canonical($post->link()); HMeta::description($metas['descr']); HMeta::keywords($metas['keywords']);
/*#if !blog.onIndexPage */HBreadcrumbs::set(array('/*#val blog_title */'=>'/posts'));/*#/if*/
$v->set('col_content',$ve->render('tags'));
?>
<? $ve->incl() ?>
