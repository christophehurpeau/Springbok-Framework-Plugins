<?php
echo json_encode(array(
	'name'=>$sk->name,
	'slug'=>$sk->slug,
	'title'=>$sk->metaTitle(),
	'description'=>$sk->metaDescr(),
	'keywords'=>$sk->metaKeywords()
));