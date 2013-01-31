<? HHtml::doctype() ?>
<html>
	<head>
		<? HHtml::metaCharset() ?>
		<?php
			HHead::title(Config::$projectName.' - '.$layout_title);
			HHead::linkCssAndJs('/Dev/dev');
			HHead::jsI18n();
			HHead::favicon();
			HHead::display();
		?>
	</head>
	<body>{=$layout_content}</body>
</html>