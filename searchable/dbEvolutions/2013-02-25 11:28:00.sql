default=>ALTER TABLE `searchables_terms` ADD `normalized` varchar(100) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL AFTER `updated`
cli=>SearchablesTermRenormalizeAll
default=>CREATE unique INDEX `unique_normalized` ON `searchables_terms` ( `normalized` )