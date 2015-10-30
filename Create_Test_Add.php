<?
session_start();
?>
<?php require_once('Connections/bmks.php'); ?>
<?php require_once('Connections/bmksl.php'); ?>
<?
$login_ecode = $_SESSION["login_ecode"]; // รหัสประจำตัวบุคลากร
$login_type = $_SESSION["login_type"]; // 1 คือ นักเรียนหรือผู้ปกครอง , 2 คือครู , 3 คือบุคลากร , 4 คือผู้บริหาร
$login_name = $_SESSION["login_name"]; //ชื่อ และนามสกุลผู้ที่ login
//กันสำหรับถ้าไม่ได้ทำการ Login
$logper_type6r = $_SESSION["logper_type6r"];
$login_ecode = $_SESSION["login_ecode"];

if ($login_ecode == "") {
    $updateGoTo = "login.php";
    echo "<script>location.href='" . $updateGoTo . "'</script>";
    exit();
} else {
    if ($logper_type6r == "") {
        $updateGoTo = "warning.php";
        echo "<script>location.href='" . $updateGoTo . "'</script>";
        exit();
    }
}

$creater = $_SESSION["login_name"]; 
$yeardb = $_POST['year'];
$termdb = $_POST['term'];
$subjectdb = $_POST['subject'];
$typedb = $_POST['type'];
$subjectdb0 = substr($subjectdb, -4);
$subjectdb1 = substr($subjectdb, 0,8);
$subjectdb2 = substr($subjectdb, 8);
$subjectdb3 = substr($subjectdb2, 0,-4);
//echo $subjectdb3;
mysql_query("Set names 'utf8'");
$sql = "INSERT INTO  new_test (Id_New_Test,year,term,subjectID,Subject,subjectname,type,creater) VALUES ('','$yeardb','$termdb','$subjectdb0','$subjectdb1','$subjectdb3','$typedb','$creater')";
//echo $sql;
mysql_query($sql);
header( "refresh:1;url=create_new_test.php" );


?>
<!DOCTYPE html>
<html>
<head>
	 <meta charset="utf-8" />
</head>
<body>

</body>
</html>