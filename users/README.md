
### Configuration

> config/enhance.php

```
<?php return array(
	'plugins'=>array(
		'users'=>array('SpringbokCore','users'),
	),
	
	
	// Optional
	'config'=>array(
		'users.pseudo'=>false, // default to false, 
		'user.searchable'=>true,
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