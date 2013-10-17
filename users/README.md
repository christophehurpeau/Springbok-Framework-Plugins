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

### Javascript

To be able to use validation and ajax lost password in forms, add this line in your index javascript file :

```
includePlugin('users/_users');
```


### Use the Login and Register page

By default, your login page contains a simple login form

To be able to use a page with 3 forms : login, register and a form for lost password, you need to override the view in `views/Site/login.php`  :

```
{includePlugin users/views/Site/login_register.php}
```


### Add in the header the connected user and links to My Account and Logout

Add this in your layout, in the <header>

> viewsLayouts/page.php

```
{includePlugin users/views/_connected}
```
