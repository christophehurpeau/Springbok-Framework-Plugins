<?php $v=new View($metas['title'],'blog');
HMeta::canonical($post->link()); HMeta::description($metas['descr']); HMeta::keywords($metas['keywords']);
HBreadcrumbs::set(array('/* VALUE(blog_title) */'=>'/posts'));
$v->set('col_content',$ve->render('tags'));
?>
<? $ve->render() ?>
