default=>DROP TABLE IF EXISTS searchables_keyword_keywords
default=>ALTER TABLE `searchables_keyword_terms` ADD `type` tinyint(2) unsigned NOT NULL AFTER `term_id`
default=>UPDATE `searchables_terms` SET type=0 WHERE type=1
default=>UPDATE searchables_keyword_terms skt LEFT JOIN searchables_typed_terms stt ON skt.term_id=stt.term_id SET skt.type=stt.type
default=>INSERT IGNORE INTO searchables_typed_terms(term_id,type) SELECT sk.id,st.type FROM searchables_keywords sk LEFT JOIN searchables_terms st ON sk.id=st.id WHERE st.type !=0
default=>DELETE FROM searchables_typed_terms WHERE type=0
default=>ALTER TABLE `searchables_keyword_terms` ADD `inherited_from` int(10) unsigned AFTER `term_id`