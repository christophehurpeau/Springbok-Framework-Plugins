<? HHtml::doctype() ?>
<html>
	<head>
		<? HHtml::metaCharset() ?>
		<?php
			HHead::title($layout_title);
			HHead::linkCssAndJs('/admin');
			HHead::display();
		?>
	</head>
	<body>
		{=$layout_content}
	</body>
</html>