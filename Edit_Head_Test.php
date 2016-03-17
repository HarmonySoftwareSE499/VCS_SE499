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
$subone_name = "แก้ไขหัวข้อสอบ (Edit Head Examination)"; //หัวข้อหลัก
$subtwo_name = ""; //หัวข้อย่อย
?>
<!DOCTYPE html>
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
$Id_New_Test = $_GET['Id_New_Test'];
 $sql = "SELECT * FROM new_test where Id_New_Test = $Id_New_Test";
$objQuery = mysql_query($sql);
?>
<?
while($objResult = mysql_fetch_array($objQuery))
  
{ $chk_que1 =  explode("/9j/", $objResult["text1"]);

?>
<br>
<form action="Edit_Save_Head_Test.php" method="POST">
<table>
<tr><td width="30%">ปีการศึกษา</td><td width="100%"><input name="year" size="5" style="width:90%;" type="text" value="<?=$objResult['year'];?>"><br><br></td></tr>
<tr><td width="30%">ชนิดการสอบ</td><td width="100%"><input name="type" style="width:90%;" type="text" value="<?=$objResult['type'];?>"><br><br></td></tr>
<tr><td width="30%">ภาคเรียน</td><td width="100%"><input name="term" style="width:90%;" type="text" value="<?=$objResult['term'];?>"><br><br></td></tr>
<tr><td width="30%">รหัสวิชา</td><td width="100%"><input name="Subject" style="width:90%;" type="text" value="<?=$objResult['Subject'];?>"><br><br></td></tr>
<tr><td width="30%">ชื่อวิชา</td><td width="100%"><input name="subjectname" style="width:90%;" type="text" value="<?=$objResult['subjectname'];?>"><br><br></td></tr>
<tr><td width="30%">ระดับชั้น</td><td width="100%"><input name="level" style="width:90%;" type="text" value="<?=$objResult['level'];?>"><br><br></td></tr>
<tr><td width="30%">จำนวนข้อ</td><td width="100%"><input name="point" style="width:90%;" type="text" value="<?=$objResult['point'];?>"><br><br></td></tr>
<tr><td width="30%">จำนวนข้อสอบที่ครูสามารถออกได้(%)</td><td width="100%"><input name="trpoint" style="width:90%;" type="text" value="<?=$objResult['trpoint'];?>"><br><br></td></tr>

<tr><td width="30%">คะแนน</td><td width="100%"><input name="score" style="width:90%;" type="text" value="<?=$objResult['score'];?>"><br><br></td></tr>
<tr><td width="30%">เวลาสอบ </td><td width="100%"><input name="time" style="width:90%;" type="text" value="<?=$objResult['time'];?>"><br><br></td></tr>

 

</table>
        <?
        }
        ?>
       

<input type="hidden" name="subject_id" value="<?= $_POST['subject_id']; ?>" />
       <input type="hidden" name="Id_New_Test" value="<?=$_GET['Id_New_Test']; ?>"/>                     
    <input type="submit" value="บันทึก">
   
</form>
      

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
                            <input type="submit" value="ยกเลิก" />
</form>
