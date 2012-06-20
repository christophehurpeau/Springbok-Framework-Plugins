<?php new View(_t('plugin.blog.Posts:').$postTag->name,'blog'); HMeta::canonical(array(true,$postTag->link(),'?'=>'page='.$posts->getPage()));
HBreadcrumbs::add(_t('plugin.blog.Posts'),'/post');
?>
<h1>/* VALUE(blog_title) */: {t 'plugin.blog.postsListTag'} {$postTag->name}</h1>
{if!e $postTag->descr}{=$postTag->descr}{/if}
{include _listPosts.php}