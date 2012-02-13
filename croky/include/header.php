<?php
require 'croky-functions.php';
$db=sql_conn();
/*
------  ------  ------  ----- ------    
|    |  |    |  |    | |   /  \   /
| |- -  ------  |    | |  /    \ /
| |     |  \    |    | |  \    | |
| |- -  |   \   |    | |   \   | |
|    |  |    \  |    | |    \  | |
------  ------  ------ ------ ------


By REmaxer


A project Developed,wrote and thought by REmaxer ;)
*/
?>
<?php
$adm_log=0;
if((isset($_SESSION['admin']))&&($_SESSION['admin']==1234)){
$adm_log=1;
}
$error="";
if(isset($_GET['error'])){
switch($_GET['error']){
case 'error':
$error="<font color=red>Found a error</font>";
break;
case 'done':
$error="<font color=green>Done</font>";
break;
case 'notvalid':
$error="<font color=red>Dates not valid</font>";
break;
case 'usernamealreadyused':
$error="<font color=yellow>Username already used</font>";
break;
case 'emailalreadyused':
$error="<font color=yellow>Email already used</font>";
break;
case 'emailusernamealreadyused':
$error="<font color=yellow>Email & Username already used</font>";
break;
case 'likeasospect':
$error="<font color=red>You are a sospect</font>";
break;
case 'notfound':
$error="<font color=yellow>Resource not found</font>";
break;
case 'alreadyused':
$error="<font color=yellow>It's already used</font>";
break;
case 'toolong':
$error="<font color=yellow>Username//E-Mail are too long</font>";
break;
default:
$error="";
break;
}
}
?>
<html>
<head>
<title><?php echo BLOG_TITLE;?></title>
<meta http-equiv="Content-Language" content="en">
<meta http-equiv="Content-Type" content="Content-type: text/html;charset=ISO-8859-1 ">
<meta name="description" content="a cms to make a little blog">
<meta name="keywords" content="croky,cms,blog,croky-blog,remaxer,RMX,REmaxer">
<link rel="icon" href="favicon.ico" type="image/ico">
<link rel="stylesheet" href="include/mystyle.css" type="text/css">
</head>
<body onload="init()">
<div class="center">
<a href="index.php"><div class="logo_table"></div></a>
<?php
if($error!=""){
echo'<p align=center><span align="center" class=error>'.$error.'</span></p>';
}
?>
</br>
<table class="princ">

<tr>
<td valign="top">
<table class="princ2">
<tr><td>
<table class="content_normals">
<tr><td>
<table class="normal_content">
<th><strong>Login:</strong></th>
<?php
if(isset($_SESSION['user_id'])){
$username=$_SESSION['user_id'];
$query='SELECT * FROM users WHERE user_id='.$username;
$result=sql_query($query,$db);
$row=mysql_fetch_assoc($result);
extract($row);
echo'<tr><td><span style="color:red;">Username:</span>'.deletenbsp($user_name).'</td></tr>';
echo'<tr><td><span style="color:red;">E-mail:</span>'.deletenbsp($email).'</td></tr>';
mysql_free_result($result);
?>
<form name=changemypassword  action="user_work.php" method=post>
<tr><td><label for="oldpassword">Old Psw:</label>
<input type=password name=oldpassword id=oldpassword size=4 /></td></tr>
<tr><td><label for="newpassword">New Psw:</label>
<input type=password name=newpassword id=newpassword size=4 /></td></tr>
<tr><td><input type=submit name=submit value="Change Password" /></td></tr>
<tr><td><input type=submit name=submit value="Logout" /></td></tr>
</form>
</table>
</td></tr>

<?php

}
else if($adm_log==1){
?>
<tr><td>Username:Admin</td></tr>
<tr><td><a href="admin_main.php?mode=add_post">Add a post</a></td></tr>
<tr><td><a href="admin_main.php?mode=delete_user">Delete a user</a></td></tr>
<tr><td><a href="admin_work.php?submit=Logout">Logout</a></td></tr>
</table>
</td></tr>
<?php
}
else{
?>
<form name=login action="login.php" method=post>
<tr><td><label for="username">UserName:</label>
<input type=text name=username id=username /></td></tr>
<tr><td><label for="password">Password:</label>
<input type=password name=password id=password /></td></tr>
<tr><td><input type=submit name=submit value="login" /></td></tr>
<tr><td><input type=button name=register-button id=register-button onClick="show_register()" value="Register" /></td></tr>
</form>
<script>
function init(){
document.getElementById("register-content").style.display="none";
document.getElementById("register-button").style.display="block";
document.GetElementById("register-button").focus();
}
function show_register(){
document.getElementById("register-content").style.display="block";
document.getElementById("register-button").style.display="none";
}
</script>
<tr><td>
<table class="register_content" id="register-content">
<form name=login action="register.php" method=post>
<tr><td>Register:</td></tr>
<tr><td><label for="username"> Type your UserName(Not HEX):</label>
<input type=text name=username id=username /></td></tr>
<tr><td><label for="password">Type your Password:</label>
<input type=password name=password id=password /></td></tr>
<tr><td><label for="email_1">Type your Email(Not HEX):</label>
<input type=text name=email_1 id=email_1 size="5"/>@<input type=text name=email_2 id=email_2 size="5" />.<input type=text name=email_3 id=email_3 size="2" /></td></tr>
<tr><td><input type=submit name=submit2 value="register" /></td></tr>
</form>
</table>
</table>
</td></tr>
<?php
}
?>
<tr><td>
</br>
<table class="normal_content">
<th><strong>Search:(NOT HEX)</strong></th>
<form name=search.php action=search.php method=get><tr><td><input type=text name="keyword" /></td></tr><tr><td><input type=submit name=submit value="Search" /></td></tr></form>
</table>
</tr></td>
<tr><td>
</br>
<table class="normal_content">
<th>Last <?php echo MAX_LAST_POSTS ?> posts:</th>
<?php
$query='SELECT post_id,UNIX_TIMESTAMP(post_date) AS post_date,post_title FROM posts ORDER BY post_date DESC LIMIT '.MAX_LAST_POSTS;
$result=sql_query($query,$db);
if(mysql_num_rows($result)>0){
$count=0;
while($row=mysql_fetch_array($result)){
extract($row);
$count++;
echo'<tr><td>'.date('l F j, Y H:i',$row['post_date']).'</br><div>'.$count.'-<a href="view_post.php?id='.$row['post_id'].'">'.deletenbsp($row['post_title']).'</div></a></td></tr><tr><td>-------------</td></td>';
}
mysql_free_result($result);
}
else{
echo'<tr><td>No Posts</td></tr>';
}
?>
</table>
</tr></td>
<tr>
<td>
</br>
<table class="normal_content">
<tr><td><strong>Categories:</strong></td></tr>
<?php
echo ($adm_log==1)?'<form name=add_categ action=admin_work.php method=post><tr><td><input type=text name="categ_name" /></td></tr><tr><td><input type=submit name=submit value="Add categ" /></td></tr></form>':'';
$query='SELECT * FROM categories ORDER BY categ_name';
$result=sql_query($query,$db);
if(mysql_num_rows($result)>0){
while($row=mysql_fetch_array($result)){
extract($row);
$query='SELECT * FROM posts WHERE categ_id='.mysql_real_escape_string($categ_id,$db);
$result2=sql_query($query,$db);
$adm_link=(($adm_log==1)&&($categ_id!=1))?'<a href="admin_main.php?mode=confirm&submit=categ_del&id='.$categ_id.'"><span style="color:green;">Delete</span></a>':'';
echo'<tr><td><div><a href="index.php?categ='.$categ_id.'">'.deletenbsp($categ_name).'('.mysql_num_rows($result2).')</a></br>'.$adm_link.'</div></td><tr><tr><td>-------------</td></tr>';

}
mysql_free_result($result);
}
else{
echo'<tr><td>Categories not found</td></tr>';

}
?>
</table>
</td></tr>
</table>
</td>
</tr>
</table>
</td>











