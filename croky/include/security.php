<?php
function alert_access_admin(){
$ip=$_SERVER['REMOTE_ADDR'];
$browser=$_SERVER['HTTP_USER_AGENT'];
$host=gethostbyaddr($ip);
$referred=$_SERVER['HTTP_REFERER'];
$success=mail(ADMIN_EMAIL,'Problem...','Hello RMX, At the hour:'.date("G:i").'  in the day:'.date("d-m-y").' a person(!!) tried to access your administor page, had  IP '.$ip.', Browser :'.$browser.' with HOST '.$host.'Refererr '.$referred.'...I think that we must kill himXD......LOL');
return $success;

}
function alert_try_sqlinj(){
$ip=$_SERVER['REMOTE_ADDR'];
$browser=$_SERVER['HTTP_USER_AGENT'];
$host=gethostbyaddr($ip);
$referred=$_SERVER['HTTP_REFERER'];
$success=mail(ADMIN_EMAIL,'Problem...','Hello RMX, At the hour:'.date("G:i").'  in the day:'.date("d-m-y").' a person(!!) tried to do a SQL INJection on a site made by REmaxer (ahahha!LOL), had  IP '.$ip.', Browser :'.$browser.' with HOST '.$host.'Refererr '.$referred.'...I think that we must kill himXD......LOL');
return $success;
}

function validate($string,$db){
$string1=mysql_real_escape_string(htmlentities($string,ENT_QUOTES),$db);
$string2=str_replace(" ",'&nbsp;',$string1);
return $string2;
}

function deletenbsp($string){
$string1=str_replace('&nbsp;'," ",$string);
$string2=stripslashes($string1);
return $string2;
}

function addnbsp($string){
$string1=str_replace(" ",'&nbsp;',$string);
return $string1;
}
?>




