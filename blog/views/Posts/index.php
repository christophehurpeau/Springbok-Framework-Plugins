<?php new AjaxContentView(_t('plugin.blog.Posts'),'blog'); HMeta::canonical(array(true,'/post','?'=>'page='.$posts->getPage())) ?>

/* IF(blog_title) */<h1>/* VALUE(blog_title) */</h1>/* /IF */
{include _listPosts.php}