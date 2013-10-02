
### Configuration

> config/enhance.php

```
<?php return array(
	'plugins'=>array(
		'users'=>array('SpringbokCore','users'),
	),

);
```

### Add methods in Site Controller


```
<?php
class SiteController extends AController{
	/* @ImportAction('core','Site','login') */
	/* @ImportAction('core','Site','logout') */
}
```