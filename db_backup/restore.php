#!/usr/local/bin/php
<?php
date_default_timezone_set("Europe/Paris");

define('ENV', "staging");

chdir(dirname(__FILE__));

$db_file = require('../staging.sovafrem.com/config/database.inc.php');
$db = $db_file["db"][ENV];
$db_prod = $db_file["db"]["production"];

$mysql_savedb_filename = "snapshots/backup_".$db_prod['database']."_".strftime("%Y%m%d", time()-3600*24).".sql";

$mysql_cnx = "mysql --host={$db["host"]} --port={$db["port"]} --user={$db["user"]} --password={$db["password"]} {$db["database"]}";

$tables = `$mysql_cnx -e 'SHOW TABLES' | awk '{ print $1}' | grep -v '^Tables'`;
$tables = explode("\n", $tables);

system("$mysql_cnx -e 'SET FOREIGN_KEY_CHECKS = 0'");

foreach($tables as $table){
  system("$mysql_cnx -e 'DROP TABLE IF EXISTS {$table}'");
}

system("$mysql_cnx -e 'SET FOREIGN_KEY_CHECKS = 1'");

system("cp {$mysql_savedb_filename}.gz {$mysql_savedb_filename}2.gz");
system("gzip -d {$mysql_savedb_filename}2");
system("$mysql_cnx < {$mysql_savedb_filename}2");
system("rm {$mysql_savedb_filename}2");

?>