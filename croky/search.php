<?php
require 'include/header.php';
echo'<td valign="top"><table class="princ3"><tr><td><table class=posts_content><tr><td><h1 style="color:gray;text-align:center;">Search</h1></td></tr><tr><td>';
?>
<?php
if((isset($_GET['keyword']))&&($_GET['keyword']!="")){
$keyword=urldecode($_GET['keyword']);
if(ctype_xdigit($keyword)){
die();
}
$keyword_link='&keyword='.$keyword;
$query='SELECT post_id,UNIX_TIMESTAMP(post_date) AS post_date FROM posts  WHERE MATCH (post_title,post_text) AGAINST ("'.validate($keyword,$db).'" IN BOOLEAN MODE) ORDER BY MATCH (post_title,post_text) AGAINST ("'.validate($keyword,$db).'" IN BOOLEAN MODE) DESC'; 
$result=sql_query($query,$db);
$pnum=mysql_num_rows($result);
if($pnum<=0){
echo'<h2 style="text-align:center;">No results found</h2>';
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
$query='SELECT post_id,UNIX_TIMESTAMP(post_date) AS post_date FROM posts  WHERE MATCH (post_title,post_text) AGAINST ("'.validate($keyword,$db).'" IN BOOLEAN MODE) ORDER BY MATCH (post_title,post_text) AGAINST ("'.validate($keyword,$db).'" IN BOOLEAN MODE) DESC LIMIT '.$limit.','.$pforp;
$result=sql_query($query,$db);
while($row=mysql_fetch_array($result)){
extract($row);
post_show($post_id,TRUE,$db,$adm_log);
}
mysql_free_result($result);
if($pages!=1){
if($page==1)
{
echo'<a href="search.php?page='.($page+1).$keyword_link.'"> Old Posts >> </a>';
}
elseif($page==$pages)
{
echo'<a href="search.php?page='.($page-1).$keyword_link.'"> << New Posts </a>';
}
else{
echo'<a href="search.php?page='.($page-1).$keyword_link.'"> << New Posts </a>||<a href="search.php?page='.($page+1).$keyword_link.'"> Old Posts >> </a>';

}
}
}
}
else{
die();

}
?>
<?php
require 'include/footer.php';
?>
