default=>ALTER TABLE post_tags DROP FOREIGN KEY fk_post_tags_tag_id,DROP FOREIGN KEY fk_post_tags_post_id, DROP PRIMARY KEY
default=>CREATE TABLE posts_tags_backup20130124 SELECT * FROM posts_tags
default=>UPDATE post_tags pt LEFT JOIN posts_tags pst ON pst.id=pt.tag_id SET pt.tag_id=pst.p_id
default=>ALTER TABLE posts_tags CHANGE `id` `id` INT( 10 ) UNSIGNED NOT NULL, DROP FOREIGN KEY fk_posts_tags_p_id
default=>ALTER TABLE posts_tags DROP INDEX unique_p_id
default=>ALTER TABLE posts_tags DROP PRIMARY KEY
default=>UPDATE posts_tags SET id=p_id
