<?php
echo json_encode(array(
	'title'=>$post->meta_title,
	'descr'=>$post->meta_descr,
	'keywords'=>$post->meta_keywords
));