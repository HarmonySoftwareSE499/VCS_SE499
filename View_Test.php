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
?>
<!-- สิ้นสุดหัว -->
<?php include '../mainsystem/inc_startpage.php'; ?>
<?
$maintitile_name = "ระบบบันทึกผลการเรียน"; //ชื่อโปรแกรม และหัวเว็บ
$subtitile_name = "Assessment Record System"; //คำอธิบายโปรแกรม
$subone_name = "เลือกข้อสอบ (Choose Test)"; //หัวข้อหลัก
$subtwo_name = ""; //หัวข้อย่อย

//- See more at: http://sixhead.com/2008/09/28/easy-export-to-microsoft-word/#sthash.tgoLHWHZ.dpuf
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:word"
      xmlns="http://www.w3.org/TR/REC-html40">

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<head>
 
</head>
<body>
  <div align="center">
  <img width="100px" height="80px"  src="img/varee_logo.jpg">
    <h1 align="center">โรงเรียนวารีเชียงใหม่ อำเภอเมือง จังหวัดเชียงใหม่</h1>
    <div align="center"><h3>ข้อสอบ<?echo $objResult['typetest']."\t";?>วิชา <?echo $objResult['Subject_ID'];?><? echo $objResult['Description'];?>ชั้น <? echo $objResult['grade'];?> ประจำปีการศึกษา <? echo $objResult['Year'];?> เวลา <?echo $objResult['time']."\t"."นาที";?></h4></div>
    
    <div align="center"><h4>--------------------------------------------------------------------------------------------------------------------------------</h4></div>
<?

$Id_New_Test = $_GET['Id_New_Test'];
$strSQL = "SELECT *
FROM subject
INNER JOIN new_test
ON subject.SCODE=new_test.subject Where Id_New_Test = $Id_New_Test ;";
//$strSQL = "SELECT * FROM new_test where Id_New_Test = $Id_New_Test ";
$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
while($objResult = mysql_fetch_array($objQuery))
{?>
  <?  
$strSQL1 ="SELECT DISTINCT *
FROM new_test 
INNER JOIN reference_test
ON new_test.Id_New_Test= reference_test.Id_New_Test
INNER JOIN db_test 
ON reference_test.IDtest = db_test.IDtest
WHERE reference_test.Id_New_Test = $Id_New_Test
";
$objQuery1  = mysql_query($strSQL1);
?>

<div class="col-md-10 col-md-offset-1 col-md-offset-6">

<table width="100%" style="font-size:20px;" class="table table-condensed"  >
   <tr width="100%">      
    </tr>
<?
$i = 1;
while($objResult1 = mysql_fetch_array($objQuery1))
{
?>

    <tr>
        <td width="100%"><?
                  echo $i." ). ";
                            echo $objResult1["text1"]."<BR>"; 
                            if (strlen($objResult1["c1"]) > 5000 ) {
                             
                             ?>1. <img style="width:100px; float:none;" src="data:image/jpg;base64,<?=$objResult1["c1"]?>"><BR><?
                           }else{
                            echo "1. ".$objResult1["c1"]."<BR>"; 
                                }
                                  if (strlen($objResult1["c2"]) > 5000 ) {
                             ?>2. <img style="width:100px; float:none;" src="data:image/jpg;base64,<?=$objResult1["c2"]?>"><BR><?
                           }else{
                            echo "2. ".$objResult1["c2"]."<BR>"; 
                                }
                                if (strlen($objResult1["c3"]) > 5000 ) {
                           ?>3. <img style="width:100px; float:none;" src="data:image/jpg;base64,<?=$objResult1["c3"]?>"><BR><?
                           }else{
                            echo "3. ".$objResult1["c3"]."<BR>"; 
                        }if (strlen($objResult1["c4"]) > 5000 ) {
                           ?>4. <img style="width:100px; float:none;" src="data:image/jpg;base64,<?=$objResult1["c4"]?>"><BR><?
                           }else{
                            echo "4. ".$objResult1["c4"]."<BR>";
                        }
                           $i++;
                            ?></td>
  </tr>
        <?php
 }
 ?>
</table>
  
  

<?
}
?>

</body>
</html>
<script type="text/javascript">
  window.print();

</script>


