<?php
require 'include/mysql-db.php';
require 'include/security.php';
session_start();
$db=sql_conn();
if((isset($_SESSION['user_id']))||(isset($_SESSION['admin']))){
header('Location:index.php?error=error');
die();
}
if((isset($_POST['submit2']))&&($_POST['submit2']=="register")){
$password=($_POST['password']!="")?$_POST['password']:"";
$user=($_POST['username']!="")?$_POST['username']:"";
if(($user==ADMIN_USER)&&($password==ADMIN_PASSWORD)){
header('Location:index.php?error=notvalid');
die();
}
$email_1=($_POST['email_1']=="")?$_POST['email_1']:"";
$email_2=($_POST['email_2']=="")?$_POST['email_2']:"";
$email_3=($_POST['email_3']=="")?$_POST['email_3']:"";
$user=(!ctype_xdigit($_POST['username']))?$_POST['username']:"";
$email_1=(!ctype_xdigit($_POST['email_1']))?$_POST['email_1']:"";
$email_2=(!ctype_xdigit($_POST['email_2']))?$_POST['email_2']:"";
$email_3=(!ctype_xdigit($_POST['email_3']))?$_POST['email_3']:"";
if($password==""||$user==""||$email_1==""||$email_2==""||$email_3==""){
header('Location:index.php?error=notvalid');
die();
}
$email=$email_1.'@'.$email_2.'.'.$email_3;
$uau=0;
$eau=0;
$query='SELECT user_name FROM users WHERE user_name="'.validate($user,$db).'"';
$result=sql_query($query,$db);
if(mysql_num_rows($result)>0){
$uau=1;
}
$query='SELECT email FROM users WHERE email="'.validate($email,$db).'"';
$result=sql_query($query,$db);
if(mysql_num_rows($result)>0){
$eau=1;
}
if($uau==1&&$eau==1){
header('Location:index.php?error=emailusernamealreadyused');
die();
}
if($uau==1){
header('Location:index.php?error=usernamealreadyused');
die();
}
if($eau==1){
header('Location:index.php?error=emailalreadyused');
die();
}

if(strlen(validate($user,$db))>60||strlen(validate($email,$db))>60){
header('Location:index.php?error=toolong');
die();

}



$query='INSERT IGNORE INTO users (user_id,user_name,password,email) VALUES
(NULL,"'.validate($user,$db).'",PASSWORD("'.mysql_real_escape_string($password,$db).'"),"'.validate($email,$db).'")';
sql_query($query,$db);
$id=mysql_insert_id($db);
$_SESSION['user_id']=$id;
header('Location:index.php?error=done');
die();
}
else{
header('Location:index.php?error=error');
die();
}

?>
