<?php new AjaxContentView('/* VALUE(blog_title) */','blog');
/* IF!(blog.onIndexPage) */HMeta::canonical(array(true,'/post','?'=>'page='.$posts->getPage()));/* /IF */
/* IF(blog.onIndexPage) */HBreadcrumbs::setLast(false); HMeta::canonical(array('/','?'=>'page='.$posts->getPage()));/* /IF */
?>

/* IF(blog_title) */<h1>/* VALUE(blog_title) */</h1>/* /IF */
{include _listPosts.php}