
### Configuration

> config/enhance.php

```
<?php return array(
	'plugins'=>array(
		'users'=>array('SpringbokCore','users'),
	),
	
	
	// Optional
	'config'=>array(
		'users.pseudo'=>false, // default to false, if the user can have a pseudo
		'user.searchable'=>true, // default to false, if User extends from Searchable
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