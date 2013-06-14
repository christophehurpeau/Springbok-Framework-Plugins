default=>ALTER TABLE `user_connections` MODIFY `connected` INT(10) unsigned
default=>UPDATE `user_connections` SET `connected`=NULL WHERE `connected`=0