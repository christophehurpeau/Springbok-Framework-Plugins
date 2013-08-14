<?php new View('/*#val blog_title */ - '._t('plugin.blog.Posts:').' '.$postTag->name,'blog'); HMeta::canonical(array(true,$postTag->link(),'?'=>'page='.$posts->getPage()));
HBreadcrumbs::add('/*#val blog_title */','/posts');
?>

<? VSeo::create('SearchablesKeyword',$postTag->id,'/*#val blog_title */: '._t('plugin.blog.postsListTag').' '.$postTag->name)->render('view') ?>

{include _listPosts.php}