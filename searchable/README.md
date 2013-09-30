Searcheable is a basic plugin which defines a first-level tables : **Searchables** and **SearchablesKeyword**.

This plugin is used by the other plugin *Blog*.

A **term** is a word or a few words representing a idea or a concept, e.g. *Git*, *GitHub*, *Distributed Revision Control*

A **keyword** is a strong term linked to others terms, e.g. *Git* is linked with *Distributed Revision Control*

A **searchable** is a extendable model used for searchable objects optionally linked to keywords


In our blog example :

*Git* is a *tag* and also a *keyword*

The post *Git : how to create a repository* is **searchable** and is linked to the tag *Git*


![Schema](https://raw.github.com/christophehurpeau/Springbok-Framework-Plugins/master/searchable/documentation/searchables.png)


### Configuration


> config/enhance.php

```
<?php return array(
	'plugins'=>array(
		'searchable'=>array('SpringbokCore','searchable'),
	),

	'modelParents'=>array(
		'Searchable'=>array(0=>'Post',5=>'Page',6=>'CmsHardCodedPage'),
		'SearchablesKeyword'=>array(0=>'PostsTag',1=>'PostsCategory'),
	),
);
```

`modelsParents` contains all the child of a model (see BParent and BChild)
