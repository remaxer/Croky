<?php
require 'include/mysql-db.php';
require 'include/security.php';
session_start();
$db=sql_conn();
if((!isset($_SESSION['admin']))||($_SESSION['admin']!=1234)){
alert_access_admin();
header('Location:index.php?error=error');
die();
}
if(isset($_REQUEST['submit'])){
switch($_REQUEST['submit']){




//Delete Categories
case'categ_del':
if(!ctype_digit($_GET['id'])){
header('Location:index.php?error=error');
die();
}
if($_GET['id']==1){
header('Location:index.php?error=notvalid');
die();
}
$id=$_GET['id'];
$query='DELETE FROM categories WHERE categ_id='.mysql_real_escape_string($id,$db);
sql_query($query,$db);
$query='UPDATE posts
SET
categ_id
="1" WHERE categ_id='.mysql_real_escape_string($id,$db);
sql_query($query,$db);
header('Location:index.php?error=done');
die();
break;


//Add categ
case'Add categ':
$categ_name=$_POST['categ_name'];
if($categ_name==""){
header('Location:index.php?error=notvalid');
die();
}
$query='SELECT categ_name FROM categories';
$result=sql_query($query,$db);
while($row=mysql_fetch_array($result)){
if($row['categ_name']==$categ_name){
header('Location:index.php?error=alreadyused');
die();
}
}
mysql_free_result($result);
$query='INSERT IGNORE INTO categories (categ_name)
VALUES
("'.validate($categ_name,$db).'")';
sql_query($query,$db);
header('Location:index.php?error=done');
die();
break;



//Add post
case 'Add post':
$post_title=$_POST['post_title'];
$categ_id=$_POST['categ_id'];
$post_text=$_POST['post_text'];
$logo_link=((isset($_POST['logo_link']))&&($_POST['logo_link']!='http://'))?$_POST['logo_link']:"";
if($post_title==""||$post_text==""){
header('Location:index.php?error=notvalid');
die();
}
if(!ctype_digit($categ_id)){
$categ_id="";
}
$query='INSERT INTO posts (post_date,post_title,categ_id,post_text,post_edit_date,post_logo)
VALUES
("'.date('Y-m-d H:i:s').'","'.validate($post_title,$db).'",'.mysql_real_escape_string($categ_id,$db).',"'.validate($post_text,$db).'",NULL,"'.validate($logo_link,$db).'")';
sql_query($query,$db);
$post_id=mysql_insert_id();
$tags=$_POST['tags'];
	if ($tags){
	 foreach ($tags as $tag){
if(!ctype_digit($tag)){
header('Location:index.php?error=notvalid');
die();
}
$query='SELECT * FROM tags WHERE tag_id='.validate($tag,$db);
$result=sql_query($query,$db);
if(mysql_num_rows($result)<=0){
header('Location:index.php?error=error');
die();
}
mysql_free_result($result);
$query='INSERT INTO posttags (post_id,tag_id)
VALUES
('.validate($post_id,$db).','.validate($tag,$db).')';
sql_query($query,$db);
}
}
$query='INSERT INTO postviews(post_id,views)
VALUES
('.validate($post_id,$db).',0)';
sql_query($query,$db);
header('Location:index.php?error=done');
break;

//Delete post
case 'post_delete':
$post_id=$_GET['post_id'];
if($post_id==""||!ctype_digit($post_id)){
header('Location:index.php?error=notvalid');
die();
}
$query='DELETE FROM posts WHERE post_id='.mysql_real_escape_string($post_id,$db);
sql_query($query,$db);
$query='DELETE FROM comments WHERE post_id='.mysql_real_escape_string($post_id,$db);
sql_query($query,$db);
$query='DELETE FROM posttags WHERE post_id='.mysql_real_escape_string($post_id,$db);
sql_query($query,$db);
$query='DELETE FROM postviews WHERE post_id='.mysql_real_escape_string($post_id,$db);
sql_query($query,$db);
header('Location:index.php?error=done');
break;

//Edit post
case 'Edit post':
$post_id=$_POST['post_id'];
$post_title=$_POST['post_title'];
$categ_id=$_POST['categ_id'];
$post_text=$_POST['post_text'];
$logo_link=((isset($_POST['logo_link']))&&($_POST['logo_link']!='http://'))?$_POST['logo_link']:"";
if($post_title==""||$post_text==""||!ctype_digit($post_id)){
header('Location:index.php?error=notvalid');
die();
}
if(!ctype_digit($categ_id)){
$categ_id="";
}
$query='UPDATE posts
SET 
post_edit_date="'.date('Y-m-d H:i:s').'",
post_title="'.validate($post_title,$db).'",
categ_id='.mysql_real_escape_string($categ_id,$db).',
post_text="'.validate($post_text,$db).'",
post_logo="'.validate($logo_link,$db).'"
 WHERE post_id='.mysql_real_escape_string($post_id,$db);
sql_query($query,$db);
$tags=$_POST['tags'];
$query='DELETE FROM posttags WHERE post_id='.mysql_real_escape_string($post_id,$db);
sql_query($query,$db);
	if ($tags){
	 foreach ($tags as $tag){
if(!ctype_digit($tag)){
header('Location:index.php?error=notvalid');
die();
}
$query='SELECT * FROM tags WHERE tag_id='.validate($tag,$db);
$result=sql_query($query,$db);
if(mysql_num_rows($result)<=0){
header('Location:index.php?error=error');
die();
}
mysql_free_result($result);
$query='INSERT INTO posttags (post_id,tag_id)
VALUES
('.validate($post_id,$db).','.validate($tag,$db).')';
sql_query($query,$db);
}
}
header('Location:index.php?error=done');
break;
 

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
mysql_free_result($result);
$query='INSERT INTO comments(post_id,user_id,comment_date,comment_text)
VALUES 
('.mysql_real_escape_string($post_id,$db).',0,"'.date('Y-m-d H:i:s').'","'.validate($comment_text,$db).'")';
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
$query='SELECT post_id FROM comments WHERE comment_id='.mysql_real_escape_string($comment_id,$db);
$result=sql_query($query,$db);
if(mysql_num_rows($result)<=0){
header('Location:index.php?error=notfound');
die();
}
$row=mysql_fetch_array($result);
$post=$row['post_id'];
mysql_free_result($result);
$query='DELETE FROM comments WHERE comment_id='.mysql_real_escape_string($comment_id,$db);
sql_query($query,$db);
header('Location:view_post.php?id='.$post.'&error=done');
break;

//Delete user
case 'Delete user':
$user_id=$_POST['user_id'];
if(!ctype_digit($user_id)){
header('Location:index.php?error=notvalid');
die();
}
$query='DELETE FROM users WHERE user_id='.mysql_real_escape_string($user_id,$db);
$result=sql_query($query,$db);
$query='DELETE FROM comments WHERE user_id='.mysql_real_escape_string($user_id,$db);
$result=sql_query($query,$db);
header('Location:index.php?error=done');
break;

//Add tag
case 'Add tag':
$tag_name=$_POST['tag_name'];
if($tag_name==""){
header('Location:index.php?error=notvalid');
die();
}
$query='SELECT tag_name FROM tags';
$result=sql_query($query,$db);
while($row=mysql_fetch_array($result)){
if($row['tag_name']==$tag_name){
header('Location:index.php?error=alreadyused');
die();
}
}
mysql_free_result($result);
$query='INSERT IGNORE INTO tags (tag_name)
VALUES
("'.validate($tag_name,$db).'")';
sql_query($query,$db);
header('Location:admin_main.php?mode=mod-tag');
break;

//Delete tag
case 'Delete tag':
$tag_id=$_POST['tag_id'];
if(!ctype_digit($tag_id)){
header('Location:index.php?error=notvalid');
die();
}
$query='DELETE FROM tags WHERE tag_id='.mysql_real_escape_string($tag_id,$db);
$result=sql_query($query,$db);
$query='DELETE FROM posttags WHERE tag_id='.mysql_real_escape_string($tag_id,$db);
$result=sql_query($query,$db);
header('Location:admin_main.php?mode=mod-tag');
break;

//LOGOUT
case 'Logout':
session_unset();
session_destroy();
header('Location:index.php?error=done');
die();
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
