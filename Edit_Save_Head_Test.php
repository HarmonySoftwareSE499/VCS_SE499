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
//header("Content-Type: $mime;charset=$charset");

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
?>
<!-- สิ้นสุดหัว -->
<?php include '../mainsystem/inc_startpage.php'; ?>
<?
$maintitile_name = "ระบบบันทึกผลการเรียน"; //ชื่อโปรแกรม และหัวเว็บ
$subtitile_name = "Assessment Record System"; //คำอธิบายโปรแกรม
$subone_name = "แก้ไข/ลบข้อสอบ (Edit/Delete Examination)"; //หัวข้อหลัก
$subtwo_name = ""; //หัวข้อย่อย
?>
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <?php include '../mainsystem/inc_meta.php'; ?>
        <title><?= $maintitile_name; ?> - โรงเรียนวารีเชียงใหม่</title>
        <?php include '../mainsystem/inc_script.php'; ?>
        <!-- เปิดสคริป -->
     
        <!-- สิ้นสุดสคริป -->
    </head>
    <body>
        <?php include '../mainsystem/inc_head.php'; ?>
        <?php include '../mainsystem/inc_menu_point.php'; ?>
        <?php include '../mainsystem/inc_befordata.php'; ?>
        <!-- เริ่มข้อความ -->
        <center>
 
<?
$year = $_POST['year'];
$type = $_POST['type'];
$term = $_POST['term'];
$Subject = $_POST['Subject'];
$level = $_POST['level'];
$score = $_POST['score'];
$time = $_POST['time'];
$point = $_POST['point'];
$trpoint = $_POST['trpoint'];
mysql_query("Set names 'utf8'");
$strSQL = "UPDATE new_test SET ";
$strSQL .="year = '".$_POST["year"]."' ";
$strSQL .=",type = '".$_POST["type"]."' ";
$strSQL .=",term = '".$_POST["term"]."' ";
$strSQL .=",Subject = '".$_POST["Subject"]."' ";
$strSQL .=",level = '".$_POST["level"]."' ";
$strSQL .=",point = '".$_POST["point"]."' ";
$strSQL .=",trpoint = '".$_POST["trpoint"]."' ";
$strSQL .=",score = '".$_POST["score"]."' ";
$strSQL .=",time = '".$_POST["time"]."' ";
$strSQL .="WHERE Id_New_Test = '".$_POST["Id_New_Test"]."' ";
$objQuery = mysql_query($strSQL);
echo  $strSQL;
?>
<p><font color="RED">ทำการแก้ไขเรียบร้อยแล้ว</font></p>
<form align="center" action="create_new_test.php" method="POST" style="padding-top: 15px;">
                            <input type="hidden" name="subject_id" value="<?= $_POST['subject_id']; ?>" />
                            <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                            <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                            <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                            <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                            <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                            <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                            <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/>
                            <input type="hidden" name="Id_Issue" value="<?=$_POST['Id_Issue'];?>"/>  
                            <input type="submit" value="กลับสู่หน้าหลัก  ( Back )  " style="border: none;background: none;color: #2371E2;cursor: pointer;"/>
</form>