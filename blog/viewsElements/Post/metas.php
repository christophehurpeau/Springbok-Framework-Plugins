<?php
echo json_encode(array(
	'title'=>$post->meta_title,
	'descr'=>$post->metaDescr(),
	'keywords'=>$post->metaKeywords()
));