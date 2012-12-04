<? HHtml::doctype() ?>
<html>
	<head>
		<? HHtml::metaCharset() ?>
		<title><?= Config::$projectName ?> - {$layout_title}</title>
		<? HHtml::jsCompat() ?>
		<?php HHtml::cssLink('/Dev/dev'); HHtml::jsLink('/Dev/dev'); HHtml::jsI18n() ?>
		<? HHtml::favicon() ?>
	</head>
	<body>{=$layout_content}</body>
</html>