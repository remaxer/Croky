<html>
<head>
<meta http-equiv="Content-Type" content="Content-type: text/html;charset=ISO-8859-1 ">
<title>Admin-Main Page</title>
</head>
<body>
<table style="text-align:center;margin:0 auto;">
<?php
require 'include/mysql-db.php';
require 'include/security.php';
session_start();
if((!isset($_SESSION['admin']))||($_SESSION['admin']!=1234)){
alert_access_admin();
header('Location:index.php?error=error');
die();
}
$db=sql_conn();
switch($_GET['mode']){

//add_post
case "add_post":
?>
<tr><td>Add a Post</td></tr>
<tr><td>
<form name=addpost action=admin_work.php method=post>
<table>
<tr><td><label for="title">Title:</label><input type=text name=post_title id=title /></td></tr>
<tr><td><label for="logo_link">Logo link:</label><input type=text name=logo_link id=logo_link value="http://" /></td></tr>
<tr><td>
<?php
$query='SELECT * FROM categories ORDER BY categ_name';
$result=sql_query($query,$db);
if(mysql_num_rows($result)>0){
echo '<label for="category">Categories:</label><select name=categ_id id=category>';
while($row=mysql_fetch_array($result)){
extract($row);
echo'<option value='.$categ_id.'>'.deletenbsp($categ_name).'</option>';
}
echo'</select>';
}
else{
echo'Categories not Found(use Unknown)';
}
?>
</td></tr>
<tr><td><label for="text">Text:</label><textarea name=post_text  id=text rows=20 cols=50></textarea></td></tr>
<tr><td>
<?php
$query='SELECT * FROM tags ORDER BY tag_name';
$result=sql_query($query,$db);
if(mysql_num_rows($result)>0){
echo'<label for="tags">Tags:</br><span style="font-size:10px;color:red;">Use CTRL+Click to select a tag</span></label><select name=tags[] multiple="multiple" id=tags>';
while($row=mysql_fetch_array($result)){
extract($row);
echo'<option value='.$tag_id.'>'.deletenbsp($tag_name).'</option>';
}
echo'</select>';
}
else{
echo'Tags not Found';
}
?>
</td></tr>
<tr><td><a href="admin_main.php?mode=mod-tag">If you want add/delete a tag click here</a></td></tr>
<tr><td><input type=submit name=submit value="Add post" /></td></tr>
</table>
</form>
</td></tr>
<?php
break;
//edit_post
case "edit_post":
if(!ctype_digit($_GET['post_id']))
{
header('Location:index.php?error=error');
die();
}
$post_id=$_GET['post_id'];
$query='SELECT * FROM posts WHERE post_id='.mysql_real_escape_string($post_id,$db);
$result=sql_query($query,$db);
$row=mysql_fetch_array($result);
extract($row);
$categ_id2=$categ_id;
$tags=array();
$query='SELECT * FROM posttags WHERE post_id='.mysql_real_escape_string($post_id,$db);
$result=sql_query($query,$db);
if(mysql_num_rows($result)>0){
while($row=mysql_fetch_array($result)){
extract($row);
$tags[]=$tag_id;
}
}
$title=stripslashes($post_title);
$text=stripslashes($post_text);
$logo_link="";
$logo_link=($post_logo!="")?stripslashes($post_logo):'http://';
?>
<tr><td>Edit a Post</td></tr>
<tr><td>
<form name=editpost action=admin_work.php method=post>
<table>
<tr><td><label for="title">Title:</label><input type=text name=post_title id=title value="<?php echo $title ?>" /></td></tr>
<tr><td><label for="logo_link">Logo link:</label><input type=text name=logo_link id=logo_link value="<?php echo $logo_link ?>" /></td></tr>
<tr><td>
<?php
$query='SELECT * FROM categories ORDER BY categ_name';
$result=sql_query($query,$db);
if(mysql_num_rows($result)>0){
echo'<label for="category">Categories:</label><select name=categ_id>';
while($row=mysql_fetch_array($result)){
extract($row);
if($categ_id==$categ_id2){
echo'<option value='.$categ_id.' selected="selected">'.deletenbsp($categ_name).'</option>';
}
else{
echo'<option value='.$categ_id.'>'.deletenbsp($categ_name).'</option>';
}
}
echo'</select>';
}
else{
echo'Categories not Found(use Unknown)';
}
?>
</td></tr>
<tr><td><label for="text">Text:</label><textarea name=post_text  id=text rows=20 cols=50><?php echo $text ?></textarea></td></tr>
<tr><td>
<?php
$query='SELECT * FROM tags ORDER BY tag_name';
$result=sql_query($query,$db);
if(mysql_num_rows($result)>0){
echo'<label for="tags">Tags:</br><span style="font-size:10px;color:red;">Use CTRL+Click to select a tag</span></label><select name=tags[] multiple="multiple" id=tags>';
while($row=mysql_fetch_array($result)){
extract($row);
if(in_array($tag_id,$tags)){
echo'<option value='.$tag_id.' selected="selected">'.deletenbsp($tag_name).'</option>';
}
else{
echo'<option value='.$tag_id.'>'.deletenbsp($tag_name).'</option>';
}
}
echo'</select>';
}
else{
echo'Tags not Found';
}
?>
</td></tr>
<tr><td><a href="admin_main.php?mode=mod-tag">If you want add/delete a tag click here</a></td></tr>
<tr><td><input type=hidden name=post_id value="<?php echo $post_id ?>" /></td></tr>
<tr><td><input type=submit name=submit value="Edit post" /></td></tr>
</table>
</form>
</td></tr>
<?php
break;
//delete_user
case "delete_user":
?>
<tr><td>Delete a User</td></tr>
<tr><td><font size=2>This will delete all comments of that user!!</font></td></tr>
<tr><td>
<form name=deleteuser action=admin_work.php method=post>
<table>
<tr><td>
<?php
$query='SELECT user_id,user_name FROM users ORDER BY user_name ASC';
$result=sql_query($query,$db);
if(mysql_num_rows($result)>0){
echo'<select name=user_id>';
while($row=mysql_fetch_array($result)){
extract($row);
echo'<option value='.$user_id.'>'.deletenbsp($user_name).'</option>';
}
echo'</select></td></tr>
<tr><td><input type=submit name=submit value="Delete user" /></td></tr>';
}
else{
echo'No user found';
}
?>

</table>
</form>
</td></tr>
<?php
break;

//Add & Delete a tag
case 'mod-tag':

?>

<tr><td>Add/Delete a Tag</td></tr>
<tr><td>
<form name=add/deletetag action=admin_work.php method=post>
<table>
<tr><td><label for="tag_name">Tag Name:</label><input type=text name=tag_name id=tag_name /></td><td><input type=submit name=submit value="Add tag" /></td></tr>
<tr><td>
<?php
$query='SELECT * FROM tags ORDER BY tag_name';
$result=sql_query($query,$db);
if(mysql_num_rows($result)>0){
echo'<select name=tag_id>';
while($row=mysql_fetch_array($result)){
extract($row);
echo'<option value='.$tag_id.'>'.deletenbsp($tag_name).'</option>';
}
echo'</select></td><td><input type=submit name=submit value="Delete tag" /></td></tr>';
}
else{
echo'Tags not Found';
}
?>

<tr><td><a href="admin_main.php?mode=add_post">Back to Add post>></a></td></tr>

<?php
break;


//Confirm
case 'confirm':
$queryhttp=$_SERVER['QUERY_STRING'];
$queryhttp=str_replace('mode=confirm','',$queryhttp);
$pos=strpos('&',$queryhttp);
$queryhttp=substr($queryhttp,($pos+1));
?>
<?php
echo'<script>
function go(){
location.href="admin_work.php?'.$queryhttp.'";
}
</script>';
?>
<tr><td>Are you sure?</td></tr>
<tr><td><input type=button value="yes" onClick="go();" />&nbsp;<input type=button value="no" onClick="history.go(-1);"/></td></tr>

<?php
break;


default:
header('Location:index.php?error=error');
die();
}
?>
<tr><td><a href="index.php">Back to index>></a></td></tr>
</table>
</body>
</html>
