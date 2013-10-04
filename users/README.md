
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

### Use the Login and Register page

By default, your login page contains a simple login form

To be able to use a page with 3 forms : login, register and a form for lost password, you need to override the view in `views/Site/login.php`  :

```
{includePlugin users/views/Site/login_register.php}
```