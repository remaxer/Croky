<?php
require 'include/header.php';
echo'<td valign="top"><table class="princ3"><tr><td><table class=posts_content><tr><td><h1 style="color:gray;text-align:center;">Post</h1></td></tr><tr><td>';
?>
<?php
$id=(isset($_GET['id']))?$_GET['id']:"";
if($id==""||!ctype_digit($id)){
die();
}
$query='SELECT * FROM posts WHERE post_id='.mysql_real_escape_string($id,$db);
$result=sql_query($query,$db);
if(mysql_num_rows($result)<=0){
die();
}
post_show($id,FALSE,$db,$adm_log);
?>
<?php
require 'include/footer.php';
?>
