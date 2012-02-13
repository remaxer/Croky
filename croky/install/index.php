<?php
if(isset($_POST['submit'])){
header('install.php');
}
?>
<html>
<head>
<title>
Welcome On Croky
</title>
</head>
<body style="background-color:black;">
<form name=install action='install.php' method=post />
<p align="center">
<font color="silver" size=5>
Welcome on Croky
</font></br>
<font color="red" size=3>
Before starting installation,</br>
Are you sure that you've configured croky?(In the /include/conf.php)</br>
And are you sure that you've a database MYSQL?,</br>
And are you sure that you've read license and readme?.</br>
</font></br>
<font color="green" size=4>
If it's yes click 
</font></br>
<input type=submit name=submit value="Install!" style="margin:0 auto;text-align:center;" /></br>
<font color="blue" size=4>
Else do it!!! 
</font></br>
</p>
</form>
</body>
</html>
