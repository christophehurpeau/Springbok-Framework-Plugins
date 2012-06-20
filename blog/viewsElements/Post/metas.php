<?php
echo json_encode(array(
	'title'=>$post->metaTitle(),
	'descr'=>$post->metaDescr(),
	'keywords'=>$post->metaKeywords()
));