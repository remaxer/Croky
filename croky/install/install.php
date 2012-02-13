<?php
require '../include/mysql-db.php';
$db=mysql_connect(SQL_HOST,SQL_USER,SQL_PASS) or die ('Connect Dates are wrongs');
$query='CREATE DATABASE IF NOT EXISTS '.SQL_DB;
$result=sql_query($query,$db);
mysql_close($db);
$db=sql_conn();
$query='CREATE TABLE IF NOT EXISTS users(
user_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
user_name VARCHAR(60)   NOT NULL UNIQUE,
password  CHAR(100)      NOT NULL, 
email   VARCHAR(60)     NOT NULL UNIQUE,
PRIMARY KEY (user_id)
)ENGINE=MyISAM';
$result=sql_query($query,$db);
$query='CREATE TABLE IF NOT EXISTS posts(
post_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
post_date DATETIME NOT NULL ,
post_title VARCHAR(255) NOT NULL DEFAULT "",
categ_id INTEGER UNSIGNED NOT NULL DEFAULT 1,
post_text MEDIUMTEXT NOT NULL DEFAULT "",
post_edit_date DATETIME ,
post_logo VARCHAR(255) NOT NULL DEFAULT "",
PRIMARY KEY (post_id),
FOREIGN KEY (categ_id) REFERENCES categories(categ_id),
FULLTEXT INDEX (post_title,post_text)
)ENGINE=MyISAM';
$result=sql_query($query,$db);
$query='CREATE TABLE IF NOT EXISTS comments(
comment_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
post_id INTEGER UNSIGNED NOT NULL,
user_id  INTEGER UNSIGNED NOT NULL, 
comment_date DATETIME NOT NULL ,
comment_text MEDIUMTEXT NOT NULL DEFAULT "",
PRIMARY KEY (comment_id),
FOREIGN KEY (post_id) REFERENCES posts(post_id),
FOREIGN KEY (user_id) REFERENCES users(user_id)
)ENGINE=MyISAM';
$result=sql_query($query,$db);
$query='CREATE TABLE IF NOT EXISTS tags(
tag_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
tag_name VARCHAR(100) NOT NULL UNIQUE DEFAULT "",
PRIMARY KEY (tag_id)
)ENGINE=MyISAM';
$result=sql_query($query,$db);
$query='CREATE TABLE IF NOT EXISTS categories(
categ_id INTEGER UNSIGNED NOT NULL AUTO_INCREMENT,
categ_name VARCHAR(100) NOT NULL UNIQUE DEFAULT "",
categ_desc VARCHAR(200) NOT NULL DEFAULT "",
PRIMARY KEY (categ_id)
)ENGINE=MyISAM';
$result=sql_query($query,$db);
$query='CREATE TABLE IF NOT EXISTS posttags(
post_id INTEGER UNSIGNED NOT NULL,
tag_id INTEGER UNSIGNED NOT NULL,
FOREIGN KEY (post_id) REFERENCES posts(post_id),
FOREIGN KEY (tag_id) REFERENCES tags(tag_id)
)ENGINE=MyISAM';
$result=sql_query($query,$db);
$query='CREATE TABLE IF NOT EXISTS postviews(
post_id INTEGER UNSIGNED NOT NULL,
views INTEGER UNSIGNED NOT NULL DEFAULT 0,
FOREIGN KEY (post_id) REFERENCES posts(post_id)
)ENGINE=MyISAM';
$result=sql_query($query,$db);
$query='INSERT IGNORE INTO categories
(categ_name,categ_desc)
VALUES
("Unknown","This is the default category, not delete it")';
$result=sql_query($query,$db);
$query='INSERT IGNORE INTO posts
(post_date,post_title,categ_id,post_text)
VALUES
("'.date('Y-m-d H:i:s').'","Default Post",1,"This is a default post,made while installation.You can remove it , login to admin panel")';
$result=sql_query($query,$db);
$post_id=mysql_insert_id();
$query='INSERT IGNORE INTO postviews
(post_id,views)
VALUES
('.mysql_real_escape_string($post_id,$db).',0)';
$result=sql_query($query,$db);
?>
<html>
<head>
<title>
Welcome On Croky
</title>
</head>
<body style="background-color:black;text-align:center;">
<span style="font-size:20px;color:green;">SuccessFully</span></br>
<p align=center>
<font color=red size=3>You are using Croky,</br>
Now you must remove this folder (/install)!!!,</br>
I hope you can use it in better mode...</font></br>
<font color=white size=1>By REmaxer</font></br>
<a href="../index.php">Go to Croky;</a>
</p>
</body>
</html>
