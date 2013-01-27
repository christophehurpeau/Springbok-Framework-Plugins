default=>RENAME TABLE `cms_images` to `library_files`
default=>RENAME TABLE `cms_albums` to `library_folders`
default=>ALTER TABLE `library_files` DROP FOREIGN KEY `fk_cms_images_album_id`
default=>ALTER TABLE `library_files` CHANGE `album_id` `folder_id` int(10) unsigned DEFAULT NULL
default=>ALTER TABLE `library_files` ADD `ext` VARCHAR(5) NOT NULL DEFAULT "jpg" AFTER `name`
default=>ALTER TABLE `library_files` ADD `type` tinyint(1) NOT NULL DEFAULT 1 AFTER `ext`


