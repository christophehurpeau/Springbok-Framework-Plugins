<?php new View('/* VALUE(blog_title) */ - '._t('plugin.blog.Posts:').' '.$postTag->name,'blog'); HMeta::canonical(array(true,$postTag->link(),'?'=>'page='.$posts->getPage()));
HBreadcrumbs::add('/* VALUE(blog_title) */','/posts');
?>

<? VSeo::create('SearchablesKeyword',$postTag->p_id,'/* VALUE(blog_title) */: '._t('plugin.blog.postsListTag').' '.$postTag->name)->render('text') ?>

{include _listPosts.php}