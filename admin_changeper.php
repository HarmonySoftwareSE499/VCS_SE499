<?php require_once('Connections/bmks.php'); ?>
<?
session_start();
$logper_type4r=$_SESSION["logper_type4r"];
$login_ecode=$_SESSION["login_ecode"];

if($login_ecode=="")
{
$updateGoTo = "login.php";
echo "<script>location.href='".$updateGoTo."'</script>";exit();
}
else
{
      	if($logper_type4r=="")
		{
		   $updateGoTo = "warning.php";
		   echo "<script>location.href='".$updateGoTo."'</script>";exit();
		}
}
?>
<?
$logper_id=$_GET["logper_id"];
$logper_type=$_GET["logper_type"];
$changell=$_GET["changell"];

if($logper_type==1)
   $query_neox = "UPDATE logper SET logper_type1='$changell' WHERE logper_id='$logper_id'";
if($logper_type==2)
   $query_neox = "UPDATE logper SET logper_type2='$changell' WHERE logper_id='$logper_id'";
if($logper_type==3)
   $query_neox = "UPDATE logper SET logper_type3='$changell' WHERE logper_id='$logper_id'";
if($logper_type==4)
   $query_neox = "UPDATE logper SET logper_type4='$changell' WHERE logper_id='$logper_id'";
if($logper_type==5)
   $query_neox = "UPDATE logper SET logper_type5='$changell' WHERE logper_id='$logper_id'";
if($logper_type==6)
   $query_neox = "UPDATE logper SET logper_type6='$changell' WHERE logper_id='$logper_id'";
if($logper_type==7)
   $query_neox = "UPDATE logper SET logper_type7='$changell' WHERE logper_id='$logper_id'";
if($logper_type==8)
   $query_neox = "UPDATE logper SET logper_type8='$changell' WHERE logper_id='$logper_id'";
if($logper_type==9)
   $query_neox = "UPDATE logper SET logper_type9='$changell' WHERE logper_id='$logper_id'";
if($logper_type==10)
   $query_neox = "UPDATE logper SET logper_type10='$changell' WHERE logper_id='$logper_id'";
   
if($logper_type==13)
   $query_neox = "UPDATE logper SET logper_type13='$changell' WHERE logper_id='$logper_id'";
if($logper_type==14)
   $query_neox = "UPDATE logper SET logper_type14='$changell' WHERE logper_id='$logper_id'";
   
if($logper_type==15)
   $query_neox = "UPDATE logper SET logper_type15='$changell' WHERE logper_id='$logper_id'";
if($logper_type==16)
   $query_neox = "UPDATE logper SET logper_type16='$changell' WHERE logper_id='$logper_id'";
if($logper_type==17) //ระบบปกครอง
   $query_neox = "UPDATE logper SET logper_type17='$changell' WHERE logper_id='$logper_id'";
if($logper_type==18) //ระบบคลังข้อสอบ
 echo  $query_neox = "UPDATE logper SET logper_type18='$changell' WHERE logper_id='$logper_id'";

mysql_select_db($database_bmks, $bmks);
$neox = mysql_query($query_neox, $bmks) or die(mysql_error());
$updateGoTo = "admin_permission.php";
echo "<script>location.href='".$updateGoTo."'</script>";exit();


?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Please wait...</title>
</head>

<body>
Please wait...
</body>
</html>