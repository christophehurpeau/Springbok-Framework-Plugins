<?php new AjaxContentView('/*#val blog_title */','blog');
/*#if !blog.onIndexPage */HMeta::canonical(array(true,'/post','?'=>'page='.$posts->getPage()));
/*#else */HBreadcrumbs::setLast(false); HMeta::canonical(array('/','?'=>'page='.$posts->getPage()));
/*#/if*/
?>

/*#if blog_title*/<h1>/*#val blog_title */</h1>/*#/if*/
{include _listPosts.php}