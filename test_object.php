<?php @session_start();
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
mysql_select_db($database_bmks, $bmks);
require_once 'subject.php';
require_once 'encodeUrl/encodeUrl.php';
//$charset = "utf-8";
//$mime = "text/html";
//header("Content-Type: $mime;charset=$charset");

mysql_select_db($database_bmksl, $bmksl);
$query_rscat = "SELECT * FROM lindicat WHERE lindicat_sname like '$lindicat_snamexd'";
$rscat = mysql_query($query_rscat, $bmksl) or die(mysql_error());
$row_rscat = mysql_fetch_assoc($rscat);
$totalRows_rscat = mysql_num_rows($rscat);
if ($totalRows_rscat <> "" or $totalRows_rscat <> 0) {
    $message = "มีรหัสวิชานี้อยู่ในระบบแล้ว คุณไม่สามารถเพิ่มเข้าไปได้อีก กรุณาทำการแก้ไขจากรหัสวิชาดังกล่าว";
    echo "<SCRIPT>alert('$message');</SCRIPT>";
    echo "<script>location.href='indicate_add.php'</script>";
    exit();
}
mysql_select_db($database_bmksl, $bmksl);
?>
<!-- สิ้นสุดหัว --><?php include '../mainsystem/inc_startpage.php'; ?>
<?
$maintitile_name = "ระบบบันทึกผลการเรียน"; //ชื่อโปรแกรม และหัวเว็บ
$subtitile_name = "Assessment Record System"; //คำอธิบายโปรแกรม
$subone_name = "การกำหนดตัวชี้วัด ( Add Indicator )"; //หัวข้อหลัก
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
        <script type="text/javascript" src="cssAutocomplete/jquery-1.9.0.js"></script>
        <style type="text/css">
            .paddingLeft{
                padding-left: 5px;
                padding-right: 5px;
            } 
            textarea{

                resize: none;
            }
        </style>
        <script src="cssAutocomplete/jquery-ui.js" ></script> 
    </head>
    <body>
 <?php include '../mainsystem/inc_head.php'; ?>
        <?php include '../mainsystem/inc_menu_point.php'; ?>
        <?php include '../mainsystem/inc_befordata.php'; ?>
        <!-- เริ่มข้อความ -->
        <?
        $arrClass = explode(",", $_POST['classroom']);
        if (isset($_POST['tname'])) {
            $_SESSION['tnameCourse'] = $_POST['tname'];  // session tname course
        }
        ?>
        <table align="center" id="head_table" style=" background: #eeeeee;padding: 15px;"> 
            <tr>
                <td><b>รหัสวิชา</b>
                    <span class="eng">(Code)</span>
                    &nbsp;:<?= $_POST['subject']; ?></td>
                <td>
                    <b>รายวิชา</b> <span class="eng">(Subject)</span> &nbsp;: <?= $_POST['subject_name']; ?>
                </td>
            </tr>
            <tr>
                <td><b>ประเภทวิชา</b> <span class='eng'>(Subject type)</span>&nbsp;: <?= $_SESSION['tnameCourse']; ?> 
                </td>
                <td><b>หน่วยกิต</b> <span class="eng">(Credit)</span>&nbsp;: <?= $_POST['unit'] ?></td>
            </tr>
            <tr>
                <td><b>ช่วงชั้น</b> <span class="eng">(Keystatge) </span> : <?= $arrClass[0] . "   " . $arrClass[1]; ?></td>
                <td><b>ภาคเรียน</b> <span class='eng'>(Semester)</span> : <?= $_POST['term']; ?></td>
            </tr>
            <tr>
                <td><b>ปีการศึกษา</b>&nbsp;<span class="eng">(Year)</span>: <?= $_POST['year']; ?></td>
                <td></td>
            </tr>
        </table>  
        <?php
               //$type = $_GET['type'];
        ?>
        <br/><?
        $type = $_POST['type'];
        $subject = $_POST['subject'];
         $Id_Issue = $_POST['Id_Issue'];
        
        $strSQL1 ="SELECT question.Id_Issue, issue_question.id_course, issue_question.name_course,question.num, question.text1,question.c1,question.c2,question.c3,question.c4,question.ans,question.obj
                    FROM question
                    INNER JOIN issue_question
                    ON question.Id_Issue=issue_question.Id_Issue
                    WHERE issue_question.Id_Issue = '$Id_Issue' ";
        
        $strSQL2 = "SELECT COUNT(*) FROM question WHERE  Id_Issue =  $_POST[Id_Issue] order by num asc ";
       //echo $strSQL1;
       //echo $strSQL2;
       $objQuery  = mysql_query($strSQL1);
       $objQuery2  = mysql_query($strSQL2);
    $objResult2 = mysql_fetch_array($objQuery2);
       $n = 1;
       ?>
<table align="center" border="1" style=" border: none;background: none;color: #2371E2;">
    <tr align="center">
        <th width="70%">โจทย์</th>
        <th width="30%">ตัวชี้วัด</th>
        <?
 if ($objResult2['COUNT(*)'] == 0) {
        echo "<p align='center'><font color='red'>*กรุณาเพิ่มไฟล์ข้อสอบก่อนทำการเพิ่มตัวชี้วัด</font></p>";
}
        ?>
    </tr>
       <?
        

    //echo $objResult2['COUNT(*)'];
   
       while($objResult = mysql_fetch_array($objQuery) )
{

    ?>
    <tr>
        <td >
        <? 
        if (strlen($objResult["text1"]) > 5000 ) {
                              $chk_que1 =  explode("/9j/", $objResult["text1"]);

                             ?><? echo $n.". ".$chk_que1[0];?> <img style="width:80px; float:none;" src="data:image/jpg;base64,/9j/<?=$chk_que1[1].$chk_que1[2]?>"><BR><?
                           }else{
                            echo "$n".".".$objResult["text1"]."<BR>"; 
                                }
        ?>

        </td>
        <td align="left"><? echo $objResult['obj']."<BR/>";?></td>
    </tr>
   

    <?
    $n++;
}

        ?>
</table>

        <p align="center">-- เลือกตัวชี้วัด ( Indicator ) --</p>
        <form style="margin-left:20%;" action="Add_Test_Indicator.php" method="POST" >
        <div  align="left">
        <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/>
                                    <input type="hidden" name="type" value="<?= $_POST['type']; ?>"/> 
                                    <input type="hidden" name="Id_Issue" value="<?=$_POST['Id_Issue'];?>"/> 
                             
            <select size="10" name="lmName1">
            <?

                    $sql_indicator_for_this_course = "select * from evaulation.indicators where ";
                    $sql_indicator_for_this_course .= " evaulation.indicators.bufferId='" . $_POST['subject_id'] . "'";
                    $sql_indicator_for_this_course .= " and evaulation.indicators.indicatorscol_delete='0' ";
                    $sql_indicator_for_this_course .= " order by evaulation.indicators.idMaxCourse asc";
                    $quer_indicatoe_for_this_course = mysql_query($sql_indicator_for_this_course) or die(mysql_error());
                    $maxidInd = 1;
                    $r = 1;

                    if (mysql_num_rows($quer_indicatoe_for_this_course) <> 0) {
                        while ($row_indicator_for_this_course = mysql_fetch_array($quer_indicatoe_for_this_course)) {
                            $maxidInd = $row_indicator_for_this_course['idMaxCourse'] + 1;

                            ?>
                            <p><? echo $r."."."\t" ?><? echo  $row_indicator_for_this_course['indicatorscol_name'] ?></p>
                                <option value="<?=$row_indicator_for_this_course['indicatorscol_code']?>"><?=$r."."." "?><?=$row_indicator_for_this_course['indicatorscol_name']?></option>
                            <?php
                            $r++;
                        }
                    }
            ?>
            </select>
<script language="JavaScript">
    function chkNumber(ele)
    {
    var vchar = String.fromCharCode(event.keyCode);
    if ((vchar<'0' || vchar>'9') && (vchar != ',')) return false;
    ele.onKeyPress=vchar;
    }
</script>
            <input required  style="width:50%;" name="a1" type="text" placeholder="กรุณากรอกลำดับข้อ ของข้อสอบ เช่น...1,2,3,4" OnKeyPress="return chkNumber(this)">  
                               <br></div><br>
                                <div>
                                    <input type="submit" name="btn-upload" value="บันทึก" >

                                </div>
                                 
                                 
        </form>

        <form align="center" action="test_static.php" method="POST" style="padding-top: 15px;">
                                    <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    <input type="hidden" name="type" value="<?= $_POST['type']; ?>"/> 
                                    <input type="submit" value="เพิ่มข้อสอบที่ควรเก็บไว้" style="border: 2;background: none;color: #2371E2;cursor: pointer;"/>
                                </form> 
                                <form align="center" action="new_course_display_detail_test.php" method="POST" style="padding-top: 15px;">
                                    <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    <input type="hidden" name="type" value="<?= $_POST['type']; ?>"/>
                                    <input type="submit" value="กลับสู่หน้าหลัก  ( Back )  " style="border: 2;background: none;color: #2371E2;cursor: pointer;"/>
                                </form>
 <!--<form align="center" action="new_test.php" method="POST" style="padding-top: 15px;">
                            <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                            <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                            <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                            <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                            <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                            <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                            <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                            <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/>
                            <input type="hidden" name="type" value="<?= $_POST['type']; ?>"/>
                            <input type="submit" value="กลับสู่หน้าหลัก  ( Back )  " style="border: none;background: none;color: #2371E2;cursor: pointer;"/>
</form>-->
        </body>
