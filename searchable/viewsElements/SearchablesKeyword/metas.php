<?php
echo json_encode(array(
	'title'=>$sk->metaTitle(),
	'description'=>$sk->metaDescr(),
	'keywords'=>$sk->metaKeywords()
));