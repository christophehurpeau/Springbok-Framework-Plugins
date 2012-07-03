<?php $v=new View($metas['title'],'cms');
HMeta::canonical($page->link()); HMeta::description($metas['descr']); HMeta::keywords($metas['keywords']);
?>
<? $ve->render() ?>
