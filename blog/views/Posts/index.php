<?php new AjaxContentView('/* VALUE(blog_title) */','blog'); HMeta::canonical(array(true,'/post','?'=>'page='.$posts->getPage())) ?>

/* IF(blog_title) */<h1>/* VALUE(blog_title) */</h1>/* /IF */
{include _listPosts.php}