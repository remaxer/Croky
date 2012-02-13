<?php
require 'include/mysql-db.php';
require 'include/security.php';
session_start();
$db=sql_conn();
if(!isset($_SESSION['user_id'])){
header('Location:index.php?error=error');
die();
}
if(isset($_REQUEST['submit'])){
switch($_REQUEST['submit']){



//LOGOUT
case 'Logout':
session_unset();
session_destroy();
header('Location:index.php');
die();
break;

//Change Password
case 'Change Password':
$oldpassword=$_POST['oldpassword'];
$newpassword=$_POST['newpassword'];
$query='SELECT password FROM users WHERE user_id='.mysql_real_escape_string($_SESSION['user_id'],$db).' AND password=PASSWORD("'.mysql_real_escape_string($oldpassword,$db).'")';
$result=sql_query($query,$db);
if(mysql_num_rows($result)>0){
$query='UPDATE users SET password=PASSWORD("'.mysql_real_escape_string($newpassword,$db).'") WHERE user_id='.mysql_real_escape_string($_SESSION['user_id'],$db);
sql_query($query,$db);
header('Location:index.php?error=done');
die();
}
else{
header('Location:index.php?error=notvalid');
die();
}
//Add comment
case 'Add comment':
$post_id=$_POST['post_id'];
$comment_text=$_POST['comment_text'];
if($post_id==""||$comment_text==""||!ctype_digit($post_id)){
header('Location:index.php?error=notvalid');
die();
}
$query='SELECT * FROM posts WHERE post_id='.mysql_real_escape_string($post_id,$db);
$result=sql_query($query,$db);
if(mysql_num_rows($result)<=0){
header('Location:index.php?error=notfound');
die();
}
$query='INSERT INTO comments(post_id,user_id,comment_date,comment_text)
VALUES 
('.mysql_real_escape_string($post_id,$db).','.mysql_real_escape_string($_SESSION['user_id'],$db).',"'.date('Y-m-d H:i:s').'","'.validate($comment_text,$db).'")';
sql_query($query,$db);
header('Location:view_post.php?id='.$post_id.'&error=done');

break;




//Delete comment

case 'delete-comment':
$comment_id=$_GET['comment_id'];
if(!ctype_digit($comment_id)){
header('Location:index.php?error=notvalid');
die();
}
$query='SELECT user_id,post_id FROM comments WHERE comment_id='.mysql_real_escape_string($comment_id,$db);
$result=sql_query($query,$db);
if(mysql_num_rows($result)<=0){
header('Location:index.php?error=notfound');
die();
}
$row=mysql_fetch_array($result);
$post=$row['post_id'];
$real_user_id=$row['user_id'];
if($real_user_id!=$_SESSION['user_id']){
header('Location:index.php?error=notvalid');
die();
}
$query='DELETE FROM comments WHERE comment_id='.mysql_real_escape_string($comment_id,$db);
$result=sql_query($query,$db);
header('Location:view_post.php?id='.$post.'&error=done');
break;

//DEFAULT
default :
header('Location:index.php?error=error');
die();

}
}


else{
header('Location:index.php?error=error');
die();
}

?>
