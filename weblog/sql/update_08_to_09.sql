--
-- $Id: update_08_to_09.sql,v 1.3 2003/08/29 14:10:29 wellwine Exp $
--
-- ATTENTION:
--
-- You if your table prefix is not `xoops` you will have to manually
-- change the two SQL statements below to read `<table_prefix>_weblog`
--
-- Execute with:
-- mysql [-u username] [-p] <database_name> < update_08_to_09.sql
--

ALTER TABLE `xoops_weblog` ADD `reads` INT DEFAULT '0' NOT NULL ;
UPDATE `xoops_weblog` SET `reads`=0;
