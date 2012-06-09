<?php new View(_t('Posts:').$postTag->name,'blog'); HMeta::canonical(array(true,$postTag->link(),'?'=>'page='.$posts->getPage()));
HBreadcrumbs::add(_t('Posts'),'/post');
?>
<h1>_t('Posts:') "{$postTag->name}"</h1>
{include _listPosts.php}