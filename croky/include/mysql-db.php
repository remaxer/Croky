<?php
require 'conf.php';
//This function return the db object
function sql_conn(){
$db=mysql_connect(SQL_HOST,SQL_USER,SQL_PASS) or die ('Connect Dates are wrongs');
mysql_select_db(SQL_DB,$db);
return $db;
}
//Smaller than mysql_query
function sql_query($query,$db){
$result=mysql_query($query,$db) or die (mysql_error($db));
return $result;
}


?>
