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

// บันทึก Log ----
mysql_select_db($database_bmks, $bmks);
$myip = $_SERVER['REMOTE_ADDR'];
$query_neoxe = "INSERT INTO savelog(ECODE,savelog_type,savelog_ip) VALUES('$login_ecode','6','$myip')"; //6 จัดการตัวชี้วัด
$neoxe = mysql_query($query_neoxe, $bmks) or die(mysql_error());
//-----------------
require_once 'subject.php';
require_once 'encodeUrl/encodeUrl.php';


$charset = "utf-8";
$mime = "text/html";
header("Content-Type: $mime;charset=$charset");

//--- ตรวจสอบให้ใช้งานได้เฉพาะหัวหน้าแผนกเท่านั้น ----
/*
  ค คณิต
  ว วิทย์
  ท ภาษาไทย
  อ อังกฤษ
  พ สุขะ พละ
  ศ ศิลป
  ง การงานฯ
  ส สังคม
 */
mysql_select_db($database_bmksl, $bmksl);

//echo $totalRows_rsnews;


include '../mainsystem/inc_startpage.php';

$maintitile_name = "ระบบบันทึกผลการเรียน"; //ชื่อโปรแกรม และหัวเว็บ
$subtitile_name = "Assessment Record System"; //คำอธิบายโปรแกรม
$subone_name = "เลือกข้อสอบ (Choose Test)"; //หัวข้อหลัก
$subtwo_name = ""; //หัวข้อย่อย
$IDtest =  $_POST['IDtest'];
$Id_Issue = $_POST['Id_Issue'];
  $subject = $_POST['subject'];
 $term = $_POST['term'];
$sql_id_new_test = "SELECT Id_New_Test FROM new_test where Subject = '$subject'";
$query_id_new_test = mysql_query($sql_id_new_test);
$qu_Id_New_Test = mysql_fetch_array($query_id_new_test);
$Id_New_Test = $qu_Id_New_Test['Id_New_Test'];
 $sql = "DELETE FROM reference_test WHERE Id_New_Test = $Id_New_Test and IDtest = $IDtest";
 //echo $sql;
    mysql_query($sql);

$sql_count_use = "SELECT C_use FROM examination WHERE IDtest = '$IDtest' ";
   $query_count_use = mysql_query($sql_count_use);
        $count_result = mysql_fetch_array($query_count_use);
        $C_use1 = $count_result['C_use']-1;
         $C_use1;
         $sql_Up_count_use = "UPDATE examination SET C_use=$C_use1 WHERE IDtest = '$IDtest'";
       $objResult =  mysql_query($sql_Up_count_use);
/////////////////////////////////
  $sql_th_use = "SELECT use_point FROM new_test  where Subject = '$subject' ";
   $query_th_use = mysql_query($sql_th_use);
        $count_th_use = mysql_fetch_array($query_th_use);
        $count_th_use = $count_th_use['use_point']+1;
        $sh_th_use = $count_th_use;
         $sql_th_count_use = "UPDATE new_test SET use_point = $sh_th_use WHERE Subject = '$subject' And term = '$term'";
        mysql_query($sql_th_count_use);


       
?>

<h1 style="font-size:27px;"  name="use_point_<? echo $IDtest;?>" id="use_point_<? echo $IDtest;?>" align="right">ข้อสอบที่สามารถเลือกได้ <?=$sh_th_use;?></h1>