--
-- $Id: update_093_to_094-step2.sql,v 1.3 2003/08/29 14:10:29 wellwine Exp $
--
-- ATTENTION:
--
-- You if your table prefix is not `xoops` you will have to manually
-- change the two SQL statements below to read `<table_prefix>_weblog`
--
-- Execute with:
-- mysql [-u username] [-p] <database_name> < update_093_to_094-step2.sql
--

INSERT INTO `xoops_weblog`
SELECT *
FROM `xoops_weblog_tmp`
