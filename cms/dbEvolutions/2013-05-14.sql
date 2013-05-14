default=>
CREATE TABLE IF NOT EXISTS `slug_redirects` (  `model_name` varchar(100) NOT NULL,  `old_slug` varchar(100) NOT NULL,  `new_slug` varchar(100) NOT NULL,  `direct` char(0) DEFAULT '',  `created` datetime NOT NULL,  PRIMARY KEY (`model_name`,`old_slug`,`new_slug`)) ENGINE=InnoDB DEFAULT CHARSET=utf8;
INSERT INTO slug_redirects (model_name,old_slug,new_slug,direct,created) SELECT "Page",old_slug,new_slug,direct,created FROM page_slug_redirects;
=>END