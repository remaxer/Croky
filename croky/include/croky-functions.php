<?php
session_start();
require 'mysql-db.php';
require 'security.php';
$db=sql_conn();
function preview($text,$max=MAX_LENGTH_PREVIEW,$tail='...'){
$tail_len=strlen($tail);
$distance=$max-$tail_len;
if(strlen($text)>$max){
$temp=substr($text,0,$distance);
if (substr($text,$distance,1)==" "){
$text=$temp;
}
else{
$pos= strrpos($temp," ");
$pos_dist=$distance-$pos;
if(($pos==FALSE)||($pos_dist>40)){
$text=$temp;
}
else{
$text=substr($text,0,$pos);
}

}

$text=$text.$tail;
}
return $text;

}

function validatetextstyle($text,$max=53){
$text2=wordwrap($text,$max,"<br />",TRUE);
return $text2;
}

function htmlsostitution($text){
$text=str_replace('&lt;a href=&quot;','<a href="',$text);
$text=str_replace('&quot;&gt;','">',$text);
$text=str_replace('&lt;/a&gt;','</a>',$text);
$text=str_replace('&lt;code&gt;','<code>',$text);
$text=str_replace('&lt;/code&gt;','</code>',$text);
return $text;
}

function deletescript($text){
$text=str_replace('<script>','',$text);
$text=str_replace('</script>','',$text);
return $text;

}


function post_show($id,$preview=TRUE,$db,$adm_log=0){
if(!ctype_digit($id))
{
alert_try_sqlinj();
header('Location:index.php?error=error');
die();
}
$id=validate($id,$db);
$query='SELECT post_title,post_logo,p.categ_id,categ_name,post_text,UNIX_TIMESTAMP(post_edit_date) AS post_edit_date,UNIX_TIMESTAMP(post_date) AS post_date FROM posts p JOIN categories c ON p.categ_id=c.categ_id  WHERE  post_id='.mysql_real_escape_string($id,$db);
$result=sql_query($query,$db);
if(mysql_num_rows($result)>0)
{
while($row=mysql_fetch_assoc($result))
{
extract($row);
echo '<table class=post-tablef>
<tr><td>';
echo ($adm_log==1)?'<div>
<span class="span-edit">
<a href="admin_main.php?mode=confirm&submit=post_delete&post_id='.$id.'">
Delete this post
</a>
||
<a href="admin_main.php?mode=edit_post&post_id='.$id.'">
Edit this post
</a>
</span>
</div>':'';
echo '<div>
<div><span class="span-posttitle">
<a href="view_post.php?id='.mysql_real_escape_string($id,$db).'">
'.nl2br(deletenbsp($post_title)).'
</a>
</span>
</div>
</br>';
echo '<span class="span-submitted"><strong>
Submitted:</strong>'.htmlspecialchars(date('l F j, Y H:i',$post_date)).'
</span>
</br>';
if($post_edit_date!=""){
echo '<span class="span-submitted">
<strong>Edit Date:</strong>
'.htmlspecialchars(date('l F j, Y H:i',$post_edit_date)).'
</span>
</br>';
}
echo '
<span class="span-category">
<strong>Category:</strong>
<a href="index.php?categ='.$categ_id.'">
'.deletenbsp($categ_name).'
</a>
</span>
</br></br>';
if($post_logo!=""){
echo'<img class="logo_link" src="'.stripslashes(deletescript(html_entity_decode(deletenbsp($post_logo),ENT_QUOTES))).'"/></br></br>';
}
if($preview)
{
$text=nl2br(stripslashes(preview(htmlsostitution(deletenbsp($post_text)))));
echo '<table class="post-textf">
<tr>
<td>'.$text;
if(strlen(nl2br(stripslashes(htmlsostitution(deletenbsp($post_text)))))>MAX_LENGTH_PREVIEW){
echo'</br>
<a href="view_post.php?id='.$id.'">
More
</a>';
}
echo '</td>
</tr>
</table>';
}
else
{
echo '<table class="post-textf">
<tr>
<td>
'.nl2br(stripslashes(htmlsostitution(deletenbsp($post_text)))).'
</td>
</tr>
</table>';
}
echo'</br>
<div>
<strong>
Tags:
</strong></br>
<span class="span-tags">';
$query='SELECT * FROM
tags t JOIN posttags pt ON t.tag_id=pt.tag_id
WHERE
pt.post_id= '.mysql_real_escape_string($id,$db).' ORDER BY t.tag_name ASC ';
$result=sql_query($query,$db);
if(mysql_num_rows($result)>0)
{
while($row=mysql_fetch_array($result))
{
extract($row);
echo $tag_name.'; ';
}
}
else{
echo'No tags for this post';
}
echo'</span>
</div></br>';
if(!$preview){
add_view($id,$db);
echo '<span style="font-size:15px;">'.get_views($id,$db).'</span></br>';
show_tag_posts($id,$db);
show_comments($id,$db);
}
if($preview){
$query='SELECT * FROM comments 
WHERE 
post_id='.mysql_real_escape_string($id,$db);
$result2=sql_query($query,$db);
echo '<span style="font-size:15px;">'.get_views($id,$db).',<a href="view_post.php?id='.$id.'">
Comments:'.mysql_num_rows($result2).'
</a>
</span>
</div>';
}


}
mysql_free_result($result);
}
else{
echo 'Article Not found';
}
echo '</td>
</tr>';
echo '</table>
</br>';
}

function show_comments($id,$db){
if(!ctype_digit($id)){
alert_try_sqlinj();
header('index.php');
die();
}

$query='SELECT comment_id,c.user_id AS user_id,user_name,UNIX_TIMESTAMP(comment_date) AS comment_date,comment_text
FROM
comments c LEFT OUTER JOIN users u ON c.user_id=u.user_id
WHERE 
post_id='.mysql_real_escape_string($id,$db).' ORDER BY
comment_date DESC';
$result=sql_query($query,$db);
echo '<div><span style="font-size:15px;">Comments:'.mysql_num_rows($result).'</span></div></br>';
if (isset($_SESSION['user_id'])||isset($_SESSION['admin'])){
echo'<div><form name=comment_form action="';
echo (isset($_SESSION['admin']))?'admin_work.php':'user_work.php';
echo '" method=post>
<textarea name=comment_text id=text rows="3" cols="20"></textarea>
<input type=hidden name=post_id value='.$id.' ></br>
<input type=submit name=submit value="Add comment">
</form></div>';
}
if(mysql_num_rows($result)>0){
$comment_number=mysql_num_rows($result);
$count=$comment_number;
while($row=mysql_fetch_array($result)){
extract($row);
echo'<table class="one_comment"><tr><td><div>';
if(((isset($_SESSION['admin'])))||((isset($_SESSION['user_id'])&&$_SESSION['user_id']==$user_id))){
echo '<a href="';
echo (isset($_SESSION['admin']))?'admin_work.php':'user_work.php';
echo '?submit=delete-comment&comment_id='.$comment_id.'"><strong>Delete this comment</strong></a></br>';
}
echo'<strong>'.$count.'</strong></br>';
$count--;
echo '<strong>Submitted:</strong>'.date('l F j, Y H:i',$comment_date).'</br>
<strong>Posted By:</strong>';
echo ($user_id!=0)?deletenbsp($user_name).'</br>':'<span style="color:red;">Admin</span>'.'</br>';
echo'</div><div><table class="comment_text"><tr><td>';
echo validatetextstyle(nl2br(deletenbsp($comment_text))).'
</td></tr></table></div></td></tr></table></br>';

}
mysql_free_result($result);
}
}

function show_tag_posts($id,$db){
if(!ctype_digit($id)){
alert_try_sqlinj();
header('index.php');
die();
}
$similar=array();
$query='SELECT * FROM posttags WHERE post_id='.mysql_real_escape_string($id,$db);
$result=sql_query($query,$db);
if(mysql_num_rows($result)>0){
while($row=mysql_fetch_array($result)){
extract($row);
$query='SELECT pt.post_id AS post_id,post_title FROM posttags pt JOIN posts po ON pt.post_id=po.post_id WHERE pt.tag_id='.mysql_real_escape_string($tag_id,$db).' AND pt.post_id!='.mysql_real_escape_string($id ,$db).' ORDER BY post_id DESC LIMIT 0,'.MAX_SIMILAR_POSTS;
$result2=sql_query($query,$db);
if(mysql_num_rows($result2)>0){
while($row=mysql_fetch_array($result2)){
extract($row);
if(!array_key_exists($post_id,$similar)){
$similar[$post_id]=deletenbsp($post_title);
}
}
}
}
}
if(count($similar)>0){
echo'----------------------<div><span style="font-size:15px;">Similar posts:</span></br>';
foreach($similar as $key=>$value){
echo'<span style="font-size:12px;"><a href="view_post.php?id='.$key.'">'.$value.'</a></span></br>';

}
echo'</div>----------------------';
}
}


function get_views($post_id,$db){
if(!ctype_digit($post_id)){
header('Location:index.php?error=error');
die();
}
$query='SELECT views FROM postviews WHERE post_id='.mysql_real_escape_string($post_id,$db);
$result=sql_query($query,$db);
$row=mysql_fetch_assoc($result);
extract($row);
$views=$row['views'];
mysql_free_result($result);
return 'Views:'.$views;

}


function add_view($post_id,$db){
if(!ctype_digit($post_id)){
header('Location:index.php?error=error');
die();
}
$query='SELECT views FROM postviews WHERE post_id='.mysql_real_escape_string($post_id,$db);
$result=sql_query($query,$db);
$row=mysql_fetch_assoc($result);
extract($row);
$views=$row['views'];
mysql_free_result($result);
$query='UPDATE postviews SET
views='.($views+1).' WHERE post_id='.mysql_real_escape_string($post_id,$db);
sql_query($query,$db);


}

