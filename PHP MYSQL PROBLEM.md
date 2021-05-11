If you've encountered error about SQL,

just paste this query to the "spudvoting" database.

SET GLOBAL sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));

