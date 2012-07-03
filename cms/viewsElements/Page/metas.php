<?php
echo json_encode(array(
	'title'=>$page->metaTitle(),
	'descr'=>$page->metaDescr(),
	'keywords'=>$page->metaKeywords()
));