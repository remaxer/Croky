<?php
require 'include/header.php';
echo'<td valign="top"><table class="princ3"><tr><td><table class=posts_content><tr><td><h1 style="color:gray;text-align:center;">Posts</h1></td></tr><tr><td>';
?>
<?php
$categ="";
$categ_link="";
if((isset($_GET['categ']))&&(ctype_digit($_GET['categ']))){
$query='SELECT * FROM categories WHERE categ_id='.$_GET['categ'];
$result=sql_query($query,$db);
if(mysql_num_rows($result)>0){
$categ='WHERE categ_id='.$_GET['categ'];
$categ_link='&categ='.$_GET['categ'];
}
}
$query='SELECT post_id,UNIX_TIMESTAMP(post_date) AS post_date FROM posts '.$categ.' ORDER BY post_date DESC';
$result=sql_query($query,$db);
$pnum=mysql_num_rows($result);
if($pnum<=0){
echo'<h2 style="text-align:center;">No posts</h2>';
}else{
$pforp=MAX_P_NUMBER;
$pages=ceil($pnum/$pforp);
$page=1;
if((isset($_GET['page']))&&(ctype_digit($_GET['page']))){
if(($_GET['page']>$pages)||($_GET['page']<1)){
$page=1;
}
else{
$page=$_GET['page'];
}
}
$limit=($page-1)*$pforp;
$query='SELECT post_id,UNIX_TIMESTAMP(post_date) AS post_date FROM posts '.$categ.' ORDER BY post_date DESC LIMIT '.$limit.','.$pforp;
$result=sql_query($query,$db);
while($row=mysql_fetch_array($result)){
extract($row);
post_show($post_id,TRUE,$db,$adm_log);
}
mysql_free_result($result);
if($pages!=1){
if($page==1)
{
echo'<a href="index.php?page='.($page+1).$categ_link.'"> Old Posts >> </a>';
}
elseif($page==$pages)
{
echo'<a href="index.php?page='.($page-1).$categ_link.'"> << New Posts </a>';
}
else{
echo'<a href="index.php?page='.($page-1).$categ_link.'"> << New Posts </a>||<a href="index.php?page='.($page+1).$categ_link.'"> Old Posts >> </a>';

}
}
}
?>
<?php
require 'include/footer.php';
?>
