<!-- เริ่มหัว -->
<?php require_once('Connections/bmks.php'); ?>
<?
session_start();
$logper_type4r = $_SESSION["logper_type4r"];
$login_ecode = $_SESSION["login_ecode"];

if ($login_ecode == "") {
    $updateGoTo = "login.php";
    echo "<script>location.href='" . $updateGoTo . "'</script>";
    exit();
} else {
    if ($logper_type4r == "1") {
        $updateGoTo = "warning.php";
        echo "<script>location.href='" . $updateGoTo . "'</script>";
        exit();
    }
}
?>
<?php
if (!function_exists("GetSQLValueString")) {

    function GetSQLValueString($theValue, $theType, $theDefinedValue = "", $theNotDefinedValue = "") {
        if (PHP_VERSION < 6) {
            $theValue = get_magic_quotes_gpc() ? stripslashes($theValue) : $theValue;
        }

        $theValue = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($theValue) : mysql_escape_string($theValue);

        switch ($theType) {
            case "text":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "long":
            case "int":
                $theValue = ($theValue != "") ? intval($theValue) : "NULL";
                break;
            case "double":
                $theValue = ($theValue != "") ? doubleval($theValue) : "NULL";
                break;
            case "date":
                $theValue = ($theValue != "") ? "'" . $theValue . "'" : "NULL";
                break;
            case "defined":
                $theValue = ($theValue != "") ? $theDefinedValue : $theNotDefinedValue;
                break;
        }
        return $theValue;
    }

}

mysql_select_db($database_bmks, $bmks);
$query_rsper = "SELECT * FROM logper ORDER BY logper_id ASC";
$rsper = mysql_query($query_rsper, $bmks) or die(mysql_error());
$row_rsper = mysql_fetch_assoc($rsper);
$totalRows_rsper = mysql_num_rows($rsper);

mysql_select_db($database_bmks, $bmks);
$query_rsperx = "SELECT * FROM loguser WHERE logper_id";
$rsperx = mysql_query($query_rsperx, $bmks) or die(mysql_error());
$row_rsperx = mysql_fetch_assoc($rsperx);
$totalRows_rsperx = mysql_num_rows($rsperx);


//include ("../Connections/config_student.php");
//include ("../Connections/config_student.php");

mysql_select_db($database_bmks, $bmks);
mysql_select_db($database_bmks2, $bmks2);

//ผู้บริหาร
$pointSQL = "SELECT * FROM `point`.`loguser` pointx, `regist_employee`.`employee_t`employee WHERE pointx.ECODE = employee.ECODE AND pointx.logper_id='1'";
$point1 = mysql_query($pointSQL);
$row_point1 = mysql_fetch_assoc($point1);
$totalRows_point1 = mysql_num_rows($point1);

//ครูประถม
$pointSQL = "SELECT * FROM `point`.`loguser` pointx, `regist_employee`.`employee_t`employee WHERE pointx.ECODE = employee.ECODE AND pointx.logper_id='2'";
$point2 = mysql_query($pointSQL);
$row_point2 = mysql_fetch_assoc($point2);
$totalRows_point2 = mysql_num_rows($point2);

//ทะเบียน
$pointSQL = "SELECT * FROM `point`.`loguser` pointx, `regist_employee`.`employee_t`employee WHERE pointx.ECODE = employee.ECODE AND pointx.logper_id='3'";
$point3 = mysql_query($pointSQL);
$row_point3 = mysql_fetch_assoc($point3);
$totalRows_point3 = mysql_num_rows($point3);

//ธุรการ
$pointSQL = "SELECT * FROM `point`.`loguser` pointx, `regist_employee`.`employee_t`employee WHERE pointx.ECODE = employee.ECODE AND pointx.logper_id='4'";
$point4 = mysql_query($pointSQL);
$row_point4 = mysql_fetch_assoc($point4);
$totalRows_point4 = mysql_num_rows($point4);

//ผู้ปกครอง
$pointSQL = "SELECT * FROM `point`.`loguser` pointx, `regist_employee`.`employee_t`employee WHERE pointx.ECODE = employee.ECODE AND pointx.logper_id='5'";
$point5 = mysql_query($pointSQL);
$row_point5 = mysql_fetch_assoc($point5);
$totalRows_point5 = mysql_num_rows($point5);

//ผู้ดูแลระบบ
$pointSQL = "SELECT * FROM `point`.`loguser` pointx, `regist_employee`.`employee_t`employee WHERE pointx.ECODE = employee.ECODE AND pointx.logper_id='6'";
$point6 = mysql_query($pointSQL);
$row_point6 = mysql_fetch_assoc($point6);
$totalRows_point6 = mysql_num_rows($point6);

//ครูมัธยม
$pointSQL = "SELECT * FROM `point`.`loguser` pointx, `regist_employee`.`employee_t`employee WHERE pointx.ECODE = employee.ECODE AND pointx.logper_id='7'";
$point7 = mysql_query($pointSQL);
$row_point7 = mysql_fetch_assoc($point7);
$totalRows_point7 = mysql_num_rows($point7);

$pointSQL8 = "SELECT * FROM `point`.`loguser` pointx, `regist_employee`.`employee_t`employee WHERE pointx.ECODE = employee.ECODE AND pointx.logper_id='8'";
$point8 = mysql_query($pointSQL8);
$row_point8 = mysql_fetch_assoc($point8);
$totalRows_point8 = mysql_num_rows($point8);
//หัวหน้ากลุ่มสาระครูมัธยม
$pointSQL9 = "SELECT * FROM `point`.`loguser` pointx, `regist_employee`.`employee_t`employee WHERE pointx.ECODE = employee.ECODE AND pointx.logper_id='9'";
$point9 = mysql_query($pointSQL9);
$row_point9 = mysql_fetch_assoc($point9);
$totalRows_point9 = mysql_num_rows($point9);

//ฝ่ายทะเบียนนักเรียน
$pointSQL = "SELECT * FROM `point`.`loguser` pointx, `regist_employee`.`employee_t`employee WHERE pointx.ECODE = employee.ECODE AND pointx.logper_id='10'";
$point10 = mysql_query($pointSQL);
$row_point10 = mysql_fetch_assoc($point10);
$totalRows_point10 = mysql_num_rows($point10);

//ฝ่ายปกครอง
$pointSQL11 = "SELECT * FROM `point`.`loguser` pointx, `regist_employee`.`employee_t`employee WHERE pointx.ECODE = employee.ECODE AND pointx.logper_id='11'";
$point11 = mysql_query($pointSQL11);
$row_point11 = mysql_fetch_assoc($point11);
$totalRows_point11 = mysql_num_rows($point11);
?>
<? include ("chlock.php"); ?>
<!-- สิ้นสุดหัว -->
<?php include '../mainsystem/inc_startpage.php'; ?>
<?
$maintitile_name="ระบบบันทึกผลการเรียน";//ชื่อโปรแกรม และหัวเว็บ
$subtitile_name="Assessment Record System";//คำอธิบายโปรแกรม
$subone_name="กำหนดสิทธิของฝ่าย"; //หัวข้อหลัก
$subtwo_name=""; //หัวข้อย่อย
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<?php include '../mainsystem/inc_meta.php'; ?>
<title><?=$maintitile_name; ?> - โรงเรียนวารีเชียงใหม่</title>
<?php include '../mainsystem/inc_script.php'; ?>
<!-- เปิดสคริป -->
        <style type="text/css">
            .textAlignVer{  
                color: #FFFFFF;

                display:block;  
                  
                filter: flipv fliph;  
                -webkit-transform: rotate(-90deg);   
                -moz-transform: rotate(-90deg);   
                transform: rotate(-90deg);   
                position:relative;

                width:3px;
                white-space:nowrap;   

                font-size:12px; 
                margin:0px;
                padding:0px;
            } 
        </style>
<!-- สิ้นสุดสคริป -->
</head>
<body>
<?php include '../mainsystem/inc_head.php'; ?>
<?php include '../mainsystem/inc_menu_point.php'; ?>
<?php include '../mainsystem/inc_befordata.php'; ?>
<!-- เริ่มข้อความ -->
                    <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0">
                        <tr>
                            <td><h2>กำหนดสิทธิของฝ่าย</h2>
                                <span class="text1">สำหรับการเข้าถึงข้อมูล</span>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="200" height="300" align="center" valign="bottom" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted; BORDER-BOTTOM: #999999 1px dotted; color:#FFF">ชื่อฝ่าย</td>
                                        <td width="20" align="center" valign="bottom" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><div class="textAlignVer">ดูผลคะแนน</div></td>
                                        <td width="20" align="center" valign="bottom" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><div class="textAlignVer">ผลคะแนนทั้งหมด และสรุปผลสัมฤทธิ์</div></td>
                                        <td width="20" align="center" valign="bottom" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><div class="textAlignVer">แก้ไขคะแนน</div></td>
                                        <td width="20" align="center" valign="bottom" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><div class="textAlignVer">ออกรายงาน</div></td>
                                        <td width="20" align="center" valign="bottom" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><div class="textAlignVer">ระบบ Admin</div></td>
                                        <td width="20" align="center" valign="bottom" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><div class="textAlignVer">เนื้อหาการเรียนรู้</div></td>
                                        <td width="20" align="center" valign="bottom" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><div class="textAlignVer">ประเมินการอ่าน คิด วิเคราะห์ ระดับมัธยม</div></td>
                                        <td width="20" align="center" valign="bottom" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><div class="textAlignVer">ประเมินการคุณลักษณะ ระดับมัธยม</div></td>
                                        <td width="20" align="center" valign="bottom" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><div class="textAlignVer">รายงาน การอ่านคิดวิเคราะห์  และเขียนรายปี ระดับมัธยม</div></td>
                                        <td width="20" align="center" valign="bottom" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><div class="textAlignVer">ทะเบียนนักเรียน</div></td>
                                        <td width="20" align="center" valign="bottom" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><div class="textAlignVer">บันทึกการอ่านคิด และคุณลักษณะ ประถม ข้อมูลชุมนุม <br />และ แบบรายงงานชุมนุมรายห้อง(ประถม)</div></td>
                                        <td width="20" align="center" valign="bottom" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><div class="textAlignVer">รายงานการอ่านคิด และคุณลักษณะ ประถม</div></td>
                                        <td width="20" align="center" valign="bottom" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><div class="textAlignVer">สรุปรายปีการอ่านคิดวิเคราะห์ สำหรับประถม</div></td>
                                        <td width="20" align="center" valign="bottom" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><div class="textAlignVer">สรุปรายปีคุณลักษณะ สำหรับประถม</div></td>
                                        <td width="20" align="center" valign="bottom" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><div class="textAlignVer">ระบบฝ่ายปกครอง</div></td>
                                        <td width="20" align="center" valign="bottom" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><div class="textAlignVer">ระบบจัดการข้อสอบ</div></td>
                                    </tr>
                                    <?php do { ?>
                                        <?
                                        $logper_id = $row_rsper['logper_id'];
                                        $logper_type1 = $row_rsper['logper_type1'];
                                        $logper_type2 = $row_rsper['logper_type2'];
                                        $logper_type3 = $row_rsper['logper_type3'];
                                        $logper_type4 = $row_rsper['logper_type4'];
                                        $logper_type5 = $row_rsper['logper_type5'];
                                        $logper_type6 = $row_rsper['logper_type6'];
                                        $logper_type7 = $row_rsper['logper_type7'];
                                        $logper_type8 = $row_rsper['logper_type8'];
                                        $logper_type9 = $row_rsper['logper_type9'];
                                        $logper_type10 = $row_rsper['logper_type10'];

                                        $logper_type13 = $row_rsper['logper_type13'];
                                        $logper_type14 = $row_rsper['logper_type14'];

                                        $logper_type15 = $row_rsper['logper_type15'];
                                        $logper_type16 = $row_rsper['logper_type16'];
										$logper_type17 = $row_rsper['logper_type17']; //ระบบปกครอง
                                        $logper_type18 = $row_rsper['logper_type18'];//ระบบจัดการข้อสอบ
                                        if ($logper_type1 == 1)
                                            $bgt1 = "FFD8D8";
                                        else
                                            $bgt1 = "C8D6FE";

                                        if ($logper_type2 == 1)
                                            $bgt2 = "FFD8D8";
                                        else
                                            $bgt2 = "C8D6FE";

                                        if ($logper_type3 == 1)
                                            $bgt3 = "FFD8D8";
                                        else
                                            $bgt3 = "C8D6FE";

                                        if ($logper_type4 == 1)
                                            $bgt4 = "FFD8D8";
                                        else
                                            $bgt4 = "C8D6FE";

                                        if ($logper_type5 == 1)
                                            $bgt5 = "FFD8D8";
                                        else
                                            $bgt5 = "C8D6FE";

                                        if ($logper_type6 == 1)
                                            $bgt6 = "FFD8D8";
                                        else
                                            $bgt6 = "C8D6FE";

                                        if ($logper_type7 == 1)
                                            $bgt7 = "FFD8D8";
                                        else
                                            $bgt7 = "C8D6FE";

                                        if ($logper_type8 == 1)
                                            $bgt8 = "FFD8D8";
                                        else
                                            $bgt8 = "C8D6FE";

                                        if ($logper_type9 == 1)
                                            $bgt9 = "FFD8D8";
                                        else
                                            $bgt9 = "C8D6FE";

                                        if ($logper_type10 == 1)
                                            $bgt10 = "FFD8D8";
                                        else
                                            $bgt10 = "C8D6FE";

                                        if ($logper_type13 == 1)
                                            $bgt13 = "FFD8D8";
                                        else
                                            $bgt13 = "C8D6FE";

                                        if ($logper_type14 == 1)
                                            $bgt14 = "FFD8D8";
                                        else
                                            $bgt14 = "C8D6FE";

                                        if ($logper_type15 == 1)
                                            $bgt15 = "FFD8D8";
                                        else
                                            $bgt15 = "C8D6FE";

                                        if ($logper_type16 == 1)
                                            $bgt16 = "FFD8D8";
                                        else
                                            $bgt16 = "C8D6FE";
											
                                        if ($logper_type17 == 1)
                                            $bgt17 = "FFD8D8";
                                        else
                                            $bgt17 = "C8D6FE";
                                         if ($logper_type18 == 1)
                                            $bgt18 = "FFD8D8";
                                        else
                                            $bgt18 = "C8D6FE";
                                        ?>
                                        <tr>
                                            <td height="25" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;BORDER-LEFT: #999999 1px dotted;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $row_rsper['logper_name']; ?></td>
                                            <td align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;" bgcolor="#<?= $bgt1; ?>">
                                                <?php
                                                if ($logper_type1 == 1) {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=1&changell=2'><img src='pic/Cancel.gif' /></a>";
                                                } else {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=1&changell=1'><img src='pic/OK.gif' /></a>";
                                                }
                                                ?>
                                            </td>
                                            <td align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;" bgcolor="#<?= $bgt5; ?>"><?php
                                                if ($logper_type5 == 1) {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=5&changell=2'><img src='pic/Cancel.gif' /></a>";
                                                } else {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=5&changell=1'><img src='pic/OK.gif' /></a>";
                                                }
                                                ?></td>
                                            <td align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;" bgcolor="#<?= $bgt2; ?>">
                                                <?php
                                                if ($logper_type2 == 1) {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=2&changell=2'><img src='pic/Cancel.gif' /></a>";
                                                } else {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=2&changell=1'><img src='pic/OK.gif' /></a>";
                                                }
                                                ?></td>
                                            <td align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;"  bgcolor="#<?= $bgt3; ?>">
                                                <?php
                                                if ($logper_type3 == 1) {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=3&changell=2'><img src='pic/Cancel.gif' /></a>";
                                                } else {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=3&changell=1'><img src='pic/OK.gif' /></a>";
                                                }
                                                ?>
                                            </td>
                                            <td align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;"  bgcolor="#<?= $bgt4; ?>">
                                                <?php
                                                if ($logper_type4 == 1) {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=4&changell=2'><img src='pic/Cancel.gif' /></a>";
                                                } else {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=4&changell=1'><img src='pic/OK.gif' /></a>";
                                                }
                                                ?>
                                            </td>
                                            <td align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;"  bgcolor="#<?= $bgt6; ?>"><?php
                                                if ($logper_type6 == 1) {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=6&changell=2'><img src='pic/Cancel.gif' /></a>";
                                                } else {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=6&changell=1'><img src='pic/OK.gif' /></a>";
                                                }
                                                ?></td>
                                            <td align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;"  bgcolor="#<?= $bgt7; ?>"><?php
                                                if ($logper_type7 == 1) {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=7&changell=2'><img src='pic/Cancel.gif' /></a>";
                                                } else {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=7&changell=1'><img src='pic/OK.gif' /></a>";
                                                }
                                                ?></td>
                                            <td align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;"  bgcolor="#<?= $bgt8; ?>"><?php
                                                if ($logper_type8 == 1) {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=8&changell=2'><img src='pic/Cancel.gif' /></a>";
                                                } else {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=8&changell=1'><img src='pic/OK.gif' /></a>";
                                                }
                                                ?></td>
                                            <td align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;"  bgcolor="#<?= $bgt9; ?>"><?php
                                                if ($logper_type9 == 1) {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=9&changell=2'><img src='pic/Cancel.gif' /></a>";
                                                } else {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=9&changell=1'><img src='pic/OK.gif' /></a>";
                                                }
                                                ?>
                                            </td>
                                            <td align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;"  bgcolor="#<?= $bgt10; ?>"><?php
                                                if ($logper_type10 == 1) {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=10&changell=2'><img src='pic/Cancel.gif' /></a>";
                                                } else {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=10&changell=1'><img src='pic/OK.gif' /></a>";
                                                }
                                                ?></td>

                                            <td align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;"  bgcolor="#<?= $bgt13; ?>"><?php
                                                if ($logper_type13 == 1) {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=13&changell=2'><img src='pic/Cancel.gif' /></a>";
                                                } else {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=13&changell=1'><img src='pic/OK.gif' /></a>";
                                                }
                                                ?></td>

                                            <td align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;"  bgcolor="#<?= $bgt14; ?>"><?php
                                                if ($logper_type14 == 1) {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=14&changell=2'><img src='pic/Cancel.gif' /></a>";
                                                } else {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=14&changell=1'><img src='pic/OK.gif' /></a>";
                                                }
                                                ?></td> 

                                            <td align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;"  bgcolor="#<?= $bgt15; ?>"><?php
                                                if ($logper_type15 == 1) {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=15&changell=2'><img src='pic/Cancel.gif' /></a>";
                                                } else {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=15&changell=1'><img src='pic/OK.gif' /></a>";
                                                }
                                                ?></td> 

                                            <td align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;"  bgcolor="#<?= $bgt16; ?>"><?php
                                                if ($logper_type16 == 1) {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=16&changell=2'><img src='pic/Cancel.gif' /></a>";
                                                } else {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=16&changell=1'><img src='pic/OK.gif' /></a>";
                                                }
                                                ?></td> 
                                                
                                            <td align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;"  bgcolor="#<?= $bgt17; ?>"><?php
                                                if ($logper_type17 == 1) {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=17&changell=2'><img src='pic/Cancel.gif' /></a>";
                                                } else {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=17&changell=1'><img src='pic/OK.gif' /></a>";
                                                }
                                                ?></td> 
                                                <td align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;"  bgcolor="#<?= $bgt18; ?>"><?php
                                                if ($logper_type18 == 1) {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=18&changell=2'><img src='pic/Cancel.gif' /></a>";
                                                } else {
                                                    echo "<a href='admin_changeper.php?logper_id=$logper_id&logper_type=18&changell=1'><img src='pic/OK.gif' /></a>";
                                                }
                                                ?></td> 
                                        </tr>
                                    <?php } while ($row_rsper = mysql_fetch_assoc($rsper)); ?>
                                </table>
                                <h2>&nbsp;</h2>
                                <h2>&nbsp;</h2>
                                <h2>รายชื่อที่อยู่ในกลุ่มของผู้บริหาร ( <a href="admin_permission_add1.php?type=1">เพิ่ม</a> )</h2>
                                <span class="text1">สำหรับการเข้าถึงข้อมูล</span>
                                <table width="46%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="31%" height="30" align="center" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><h3>รหัสพนักงาน</h3></td>
                                        <td width="69%" align="center" bgcolor="#2E5CA9"><h3>ชื่อ-สกุล</h3></td>
                                    </tr>
                                    <?php do { ?>
                                        <tr>
                                            <td height="25" align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;BORDER-LEFT: #999999 1px dotted;">
                                                <?
                                                echo $row_point1["ECODE"];
                                                ?>
                                            </td>
                                            <td style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;">
                                                &nbsp;&nbsp;
                                                <?
                                                echo $row_point1["ETNAME"];
                                                echo $row_point1["ENAME"];
                                                echo " " . $row_point1["ESNAME"];
                                                ?>
                                                <?
                                                $loguser_status = $row_point1["loguser_status"];
                                                $loguser_id = $row_point1["loguser_id"];
                                                if ($loguser_status == 2)
                                                    echo " (<a href=admin_permission_del.php?loguser_id=$loguser_id>ลบ</a>)";
                                                ?>
                                            </td>
                                        </tr><?php } while ($row_point1 = mysql_fetch_assoc($point1)); ?>
                                </table>
                                <p>&nbsp;</p>
                                <h2>รายชื่อที่อยู่ในกลุ่มหัวหน้ากลุ่มสาระครูประถม( <a href="admin_permission_add1.php?type=8">เพิ่ม</a> )</h2>
                                <span class="text1">สำหรับการเข้าถึงข้อมูล</span>
                                <table width="80%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="20%" height="30" align="center" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><h3>รหัสพนักงาน</h3></td>
                                        <td width="37%" align="center" bgcolor="#2E5CA9"><h3>ชื่อ-สกุล</h3></td>
                                        <td width="43%" align="center" bgcolor="#2E5CA9"><h3>กลุ่มสาระ</h3></td>
                                    </tr>
                                    <?php do { ?>
                                        <tr>
                                            <td height="25" align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;BORDER-LEFT: #999999 1px dotted;">
                                                <?
                                                echo $row_point8["ECODE"];
                                                ?>
                                            </td>
                                            <td style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;">
                                                &nbsp;&nbsp;
                                                <?
                                                echo $row_point8["ETNAME"];
                                                echo $row_point8["ENAME"];
                                                echo " " . $row_point8["ESNAME"];
                                                ?>
                                                <?
                                                $loguser_status = $row_point8["loguser_status"];
                                                $loguser_id = $row_point8["loguser_id"];
                                                if ($loguser_status == 2)
                                                    echo " (<a href=admin_permission_del.php?loguser_id=$loguser_id>ลบ</a>)";
                                                ?>
                                            </td>
                                            <td style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;">
                                                &nbsp;&nbsp;
                                                <?
                                                $sql_gs = "select * from  loguser_department inner join department on department.dep_id = loguser_department.dep_id ";
                                                $sql_gs .= "where loguser_department.ecode ='" . $row_point8['ECODE'] . "' and loguser_department.loguser_deleted='0'";
                                                mysql_select_db("point");
                                                $querysss = mysql_query($sql_gs);
                                                $datasss = mysql_fetch_array($querysss);
                                                echo $datasss['dep_description'];
                                                ?>
                                            </td>
                                        </tr><?php } while ($row_point8 = mysql_fetch_assoc($point8)); ?>
                                </table>
                                <p>&nbsp;</p>
                                <p>&nbsp;</p>
                                <h2>รายชื่อที่อยู่ในกลุ่มของครูประถม ( <a href="admin_permission_add1.php?type=2">เพิ่ม</a> )</h2>
                                <span class="text1">สำหรับการเข้าถึงข้อมูล</span>
                                <table width="46%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="31%" height="30" align="center" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><h3>รหัสพนักงาน</h3></td>
                                        <td width="69%" align="center" bgcolor="#2E5CA9"><h3>ชื่อ-สกุล</h3></td>
                                    </tr>
                                    <?php do { ?>
                                        <tr>
                                            <td height="25" align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;BORDER-LEFT: #999999 1px dotted;"><?
                                                echo $row_point2["ECODE"];
                                                ?></td>
                                            <td style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;">&nbsp;&nbsp;
                                                <?
                                                echo $row_point2["ETNAME"];
                                                echo $row_point2["ENAME"];
                                                echo " " . $row_point2["ESNAME"];
                                                ?>
                                                <?
                                                $loguser_status = $row_point2["loguser_status"];
                                                $loguser_id = $row_point2["loguser_id"];
                                                if ($loguser_status == 2)
                                                    echo " (<a href=admin_permission_del.php?loguser_id=$loguser_id>ลบ</a>)";
                                                ?></td>
                                        </tr>
                                    <?php } while ($row_point2 = mysql_fetch_assoc($point2)); ?>
                                </table>
                                <p>&nbsp;</p>
                                <?
                                //หัวหน้ากลุ่มสาระครูมัธยม (type=8)
                                ?>
                                <h2>รายชื่อที่อยู่ในกลุ่มหัวหน้ากลุ่มสาระครูมัธยม ( <a href="admin_permission_add1.php?type=9">เพิ่ม</a> )</h2>
                                <span class="text1">สำหรับการเข้าถึงข้อมูล</span>
                                <table width="80%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="20%" height="30" align="center" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><h3>รหัสพนักงาน</h3></td>
                                        <td width="37%" align="center" bgcolor="#2E5CA9"><h3>ชื่อ-สกุล</h3></td>
                                        <td width="43%" align="center" bgcolor="#2E5CA9"><h3>กลุ่มสาระ</h3></td>
                                    </tr>
                                    <?php do { ?>
                                        <tr>
                                            <td height="25" align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;BORDER-LEFT: #999999 1px dotted;">
                                                <?
                                                echo $row_point9["ECODE"];
                                                ?>
                                            </td>
                                            <td style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;">
                                                &nbsp;&nbsp;
                                                <?
                                                echo $row_point9["ETNAME"];
                                                echo $row_point9["ENAME"];
                                                echo " " . $row_point9["ESNAME"];
                                                ?>
                                                <?
                                                $loguser_status = $row_point9["loguser_status"];
                                                $loguser_id = $row_point9["loguser_id"];
                                                if ($loguser_status == 2)
                                                    echo " (<a href=admin_permission_del.php?loguser_id=$loguser_id>ลบ</a>)";
                                                ?>
                                            </td>
                                            <td style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;">
                                                &nbsp;&nbsp;
                                                <?
                                                $sql_g = "select * from  loguser_department inner join department on department.dep_id = loguser_department.dep_id ";
                                                $sql_g .= "where loguser_department.ecode ='" . $row_point9['ECODE'] . "'  and loguser_department.loguser_deleted='0' ";
                                                mysql_select_db("point");
                                                $queryss = mysql_query($sql_g);
                                                $datass = mysql_fetch_array($queryss);
                                                echo $datass['dep_description'];
                                                ?>
                                            </td>
                                        </tr><?php } while ($row_point9 = mysql_fetch_assoc($point9)); ?>
                                </table>
                                <p>&nbsp;</p> 
                                <h2>รายชื่อที่อยู่ในกลุ่มของครูมัธยม ( <a href="admin_permission_add1.php?type=7">เพิ่ม</a> )</h2>
                                <span class="text1">สำหรับการเข้าถึงข้อมูล</span>
                                <table width="46%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="31%" height="30" align="center" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><h3>รหัสพนักงาน</h3></td>
                                        <td width="69%" align="center" bgcolor="#2E5CA9"><h3>ชื่อ-สกุล</h3></td>
                                    </tr>
                                    <?php do { ?>
                                        <tr>
                                            <td height="25" align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;BORDER-LEFT: #999999 1px dotted;"><?
                                                echo $row_point7["ECODE"];
                                                ?></td>
                                            <td style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;">&nbsp;&nbsp;
                                                <?
                                                echo $row_point7["ETNAME"];
                                                echo $row_point7["ENAME"];
                                                echo " " . $row_point7["ESNAME"];
                                                ?>
                                                <?
                                                $loguser_status = $row_point7["loguser_status"];
                                                $loguser_id = $row_point7["loguser_id"];
                                                if ($loguser_status == 2)
                                                    echo " (<a href=admin_permission_del.php?loguser_id=$loguser_id>ลบ</a>)";
                                                ?>
                                            </td>

                                        </tr>
                                    <?php } while ($row_point7 = mysql_fetch_assoc($point7)); ?>
                                </table>
                                <p>&nbsp;</p>
                                <h2>รายชื่อที่อยู่ในกลุ่มของฝ่ายทะเบียน ( <a href="admin_permission_add1.php?type=3">เพิ่ม</a> )</h2>
                                <span class="text1">สำหรับการเข้าถึงข้อมูล</span>
                                <table width="46%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="31%" height="30" align="center" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><h3>รหัสพนักงาน</h3></td>
                                        <td width="69%" align="center" bgcolor="#2E5CA9"><h3>ชื่อ-สกุล</h3></td>
                                    </tr>
                                    <?php do { ?>
                                        <tr>
                                            <td height="25" align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;BORDER-LEFT: #999999 1px dotted;"><?
                                                echo $row_point3["ECODE"];
                                                ?></td>
                                            <td style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;">&nbsp;&nbsp;
                                                <?
                                                echo $row_point3["ETNAME"];
                                                echo $row_point3["ENAME"];
                                                echo " " . $row_point3["ESNAME"];
                                                ?>
                                                <?
                                                $loguser_status = $row_point3["loguser_status"];
                                                $loguser_id = $row_point3["loguser_id"];
                                                if ($loguser_status == 2)
                                                    echo " (<a href=admin_permission_del.php?loguser_id=$loguser_id>ลบ</a>)";
                                                ?></td>
                                        </tr>
                                    <?php } while ($row_point3 = mysql_fetch_assoc($point3)); ?>
                                </table>
                                <p>&nbsp;</p>
                                <h2>รายชื่อที่อยู่ในกลุ่มของฝ่ายธุรการ ( <a href="admin_permission_add1.php?type=4">เพิ่ม</a> )</h2>
                                <span class="text1">สำหรับการเข้าถึงข้อมูล</span>
                                <table width="46%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="31%" height="30" align="center" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><h3>รหัสพนักงาน</h3></td>
                                        <td width="69%" align="center" bgcolor="#2E5CA9"><h3>ชื่อ-สกุล</h3></td>
                                    </tr>
                                    <?php do { ?>
                                        <tr>
                                            <td height="25" align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;BORDER-LEFT: #999999 1px dotted;"><?
                                                echo $row_point4["ECODE"];
                                                ?></td>
                                            <td style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;">&nbsp;&nbsp;
                                                <?
                                                echo $row_point4["ETNAME"];
                                                echo $row_point4["ENAME"];
                                                echo " " . $row_point4["ESNAME"];
                                                ?>
                                                <?
                                                $loguser_status = $row_point4["loguser_status"];
                                                $loguser_id = $row_point4["loguser_id"];
                                                if ($loguser_status == 2)
                                                    echo " (<a href=admin_permission_del.php?loguser_id=$loguser_id>ลบ</a>)";
                                                ?></td>
                                        </tr>
                                    <?php } while ($row_point4 = mysql_fetch_assoc($point4)); ?>
                                </table>
                                <p>&nbsp;</p>
                                <h2>รายชื่อที่อยู่ในกลุ่มผู้ดูแลระบบ ( <a href="admin_permission_add1.php?type=6">เพิ่ม</a> )</h2>
                                <span class="text1">สำหรับการเข้าถึงข้อมูล</span>
                                <table width="46%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="31%" height="30" align="center" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><h3>รหัสพนักงาน</h3></td>
                                        <td width="69%" align="center" bgcolor="#2E5CA9"><h3>ชื่อ-สกุล</h3></td>
                                    </tr>
                                    <?php do { ?>
                                        <tr>
                                            <td height="25" align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;BORDER-LEFT: #999999 1px dotted;"><?
                                                echo $row_point6["ECODE"];
                                                ?></td>
                                            <td style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;">&nbsp;&nbsp;
                                                <?
                                                echo $row_point6["ETNAME"];
                                                echo $row_point6["ENAME"];
                                                echo " " . $row_point6["ESNAME"];
                                                ?>
                                                <?
                                                $loguser_status = $row_point6["loguser_status"];
                                                $loguser_id = $row_point6["loguser_id"];
                                                if ($loguser_status == 2)
                                                    echo " (<a href=admin_permission_del.php?loguser_id=$loguser_id>ลบ</a>)";
                                                ?></td>
                                        </tr>
                                    <?php } while ($row_point6 = mysql_fetch_assoc($point6)); ?>
                                </table>
                                <center>
                                </center>
                                <p>&nbsp;</p>
                                <h2>รายชื่อที่อยู่ในกลุ่มของฝ่ายทะเบียนนักเรียน ( <a href="admin_permission_add1.php?type=10">เพิ่ม</a> )</h2>
                                <span class="text1">สำหรับการเข้าถึงข้อมูล</span>
                                <table width="46%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="31%" height="30" align="center" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><h3>รหัสพนักงาน</h3></td>
                                        <td width="69%" align="center" bgcolor="#2E5CA9"><h3>ชื่อ-สกุล</h3></td>
                                    </tr>
                                    <?php do { ?>
                                        <tr>
                                            <td height="25" align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;BORDER-LEFT: #999999 1px dotted;"><?
                                                echo $row_point10["ECODE"];
                                                ?></td>
                                            <td style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;">&nbsp;&nbsp;
                                                <?
                                                echo $row_point10["ETNAME"];
                                                echo $row_point10["ENAME"];
                                                echo " " . $row_point10["ESNAME"];
                                                ?>
                                                <?
                                                $loguser_status = $row_point10["loguser_status"];
                                                $loguser_id = $row_point10["loguser_id"];
                                                if ($loguser_status == 2)
                                                    echo " (<a href=admin_permission_del.php?loguser_id=$loguser_id>ลบ</a>)";
                                                ?></td>
                                        </tr>
                                    <?php } while ($row_point10 = mysql_fetch_assoc($point10)); ?>
                                </table>
                                <p>&nbsp;</p>
                                <h2>รายชื่อที่อยู่ในกลุ่มของฝ่ายปกครอง ( <a href="admin_permission_add1.php?type=11">เพิ่ม</a> )</h2>
                                <span class="text1">สำหรับการเข้าถึงข้อมูล</span>
                                <table width="46%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td width="31%" height="30" align="center" bgcolor="#2E5CA9" style="BORDER-RIGHT: #FFFFFF 1px dotted;"><h3>รหัสพนักงาน</h3></td>
                                        <td width="69%" align="center" bgcolor="#2E5CA9"><h3>ชื่อ-สกุล</h3></td>
                                    </tr>
                                    <?php do { ?>
                                        <tr>
                                            <td height="25" align="center" style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;BORDER-LEFT: #999999 1px dotted;"><?
                                                echo $row_point11["ECODE"];
                                                ?></td>
                                            <td style="BORDER-BOTTOM: #999999 1px dotted;BORDER-RIGHT: #999999 1px dotted;">&nbsp;&nbsp;
                                                <?
                                                echo $row_point11["ETNAME"];
                                                echo $row_point11["ENAME"];
                                                echo " " . $row_point11["ESNAME"];
                                                ?>
                                                <?
                                                $loguser_status = $row_point11["loguser_status"];
                                                $loguser_id = $row_point11["loguser_id"];
                                                if ($loguser_status == 2)
                                                    echo " (<a href=admin_permission_del.php?loguser_id=$loguser_id>ลบ</a>)";
                                                ?></td>
                                        </tr>
                                    <?php } while ($row_point11 = mysql_fetch_assoc($point11)); ?>
                                </table>
                            </td>
                        </tr>
                    </table>
<!-- สิ้นสุดข้อความ -->
<?php include '../mainsystem/inc_afterdata.php'; ?>
<?php include '../mainsystem/inc_footer3.php'; ?>
<?php include '../mainsystem/inc_footer2.php'; ?>
</body>
</html>
<?php include '../mainsystem/inc_endpage.php'; ?>
<?php
mysql_free_result($rsper);
?>