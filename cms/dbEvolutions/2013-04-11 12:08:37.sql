default=>
SET FOREIGN_KEY_CHECKS=0;
CREATE TABLE old_pages SELECT * FROM pages;
UPDATE `page_histories` ph LEFT JOIN pages p ON p.id=ph.page_id SET ph.page_id=p.p_id;
UPDATE `pages` SET id=p_id;


DROP TABLE IF EXISTS `pages`;
CREATE TABLE IF NOT EXISTS `pages` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(180) NOT NULL,
  `author_id` int(10) unsigned NOT NULL,
  `content` text NOT NULL,
  `status` tinyint(1) NOT NULL,
  `published` datetime DEFAULT NULL,
  `created` datetime NOT NULL,
  `updated` datetime DEFAULT NULL,
  `slug` varchar(180) NOT NULL,
  `meta_title` varchar(100) DEFAULT NULL,
  `meta_descr` varchar(200) DEFAULT NULL,
  `meta_keywords` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_slug` (`slug`),
  KEY `created` (`created`),
  KEY `updated` (`updated`),
  KEY `fk_pages_author_id` (`author_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ;
INSERT INTO pages SELECT p_id,name,author_id,content,status,published,created,updated,slug,meta_title,meta_descr,meta_keywords FROM old_pages;

SET FOREIGN_KEY_CHECKS=1;

=>END