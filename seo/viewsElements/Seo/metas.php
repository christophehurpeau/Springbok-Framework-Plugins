<?php
echo json_encode(array(
	'name'=>$seo->name(),
	'slug'=>$seo->slug,
	'title'=>$seo->metaTitle(),
	'description'=>$seo->metaDescr(),
	'keywords'=>$seo->metaKeywords()
));