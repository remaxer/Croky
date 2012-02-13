<?php
require 'include/mysql-db.php';
require 'include/security.php';
session_start();
$db=sql_conn();
if((isset($_SESSION['user_id']))||(isset($_SESSION ['admin']))){
header('Location:index.php?error=error');
die();
}
if(isset($_POST['submit'])){
switch($_POST['submit']){

// LOGIN
case 'login':
$password=($_POST['password']!="")?$_POST['password']:"";
$user=($_POST['username']!="")?$_POST['username']:"";
if(($user==ADMIN_USER)&&($password==ADMIN_PASSWORD)){
$_SESSION['admin']=1234;
header('Location:index.php');
die();
}
$user=(!ctype_xdigit($_POST['username']))?$_POST['username']:"";
if($password==""||$user==""){
alert_try_sqlinj();
header('Location:index.php?error=notvalid');
die();
}

$query='SELECT user_id FROM users WHERE user_name="'.validate($user,$db).'" AND password=PASSWORD("'.mysql_real_escape_string($password,$db).'")';
$result=sql_query($query,$db);
if(mysql_num_rows($result)>0){
$row=mysql_fetch_assoc($result);
extract($row);
$_SESSION['user_id']=$user_id;
header('Location:index.php');
die();
}
else{
header('Location:index.php?error=notvalid');
die();
}
break;


}
}


else{
header('Location:index.php?error=error');
die();
}

?>
