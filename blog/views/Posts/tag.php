<?php new View('/* VALUE(blog_title) */ - '._t('plugin.blog.Posts:').' '.$postTag->name,'blog'); HMeta::canonical(array(true,$postTag->link(),'?'=>'page='.$posts->getPage()));
HBreadcrumbs::add('/* VALUE(blog_title) */','/posts');
?>
<h1>/* VALUE(blog_title) */: {t 'plugin.blog.postsListTag'} {$postTag->name}</h1>
{if!e $postTag->descr}{=$postTag->descr}{/if}
{include _listPosts.php}