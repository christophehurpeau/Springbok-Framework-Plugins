default=>ALTER TABLE post_tags DROP FOREIGN KEY fk_post_tags_tag_id,DROP FOREIGN KEY fk_post_tags_post_id, DROP PRIMARY KEY
default=>UPDATE post_tags pt LEFT JOIN posts_tags pst ON pst.id=pt.tag_id SET pt.tag_id=pst.p_id
default=>ALTER TABLE posts_tags CHANGE `id` `id` INT( 10 ) UNSIGNED NOT NULL ,DROP PRIMARY KEY
default=>UPDATE posts_tags SET id=p_id