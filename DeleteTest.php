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
$subone_name = "สร้างข้อสอบฉบับใหม่ (Create New Test)"; //หัวข้อหลัก
$subtwo_name = ""; //หัวข้อย่อย
$IDtest = $_POST["IDtest"];
$Subject = $_POST['subject'];
$strSQL = "DELETE FROM question ";
$strSQL .="WHERE IDtest = '$IDtest'";
//echo $strSQL;
$objQuery = mysql_query($strSQL);
?>
<html xmlns="http://www.w3.org/1999/xhtml"
      xmlns:og="http://ogp.me/ns#"
      xmlns:fb="http://www.facebook.com/2008/fbml">
    <head>
        <?php include '../mainsystem/inc_meta.php'; ?>
        <title><?= $maintitile_name; ?> - โรงเรียนวารีเชียงใหม่</title>
        <?php include '../mainsystem/inc_script.php'; ?>
        <!-- เปิดสคริป -->
        <script type="text/javascript" src="cssAutocomplete/jquery-1.9.0.js"></script>
        <script type="text/javascript">
            function checkCourseStrue() {
                //check corese strute
                var subject = "<?= $_POST['subject']; ?>";
                var term = "<?= $_POST['term']; ?>";
                var year = "<?= $_POST['year']; ?>";
                var msubject = "<?= $msubject; ?>"
                $.ajax({
                    type: "POST",
                    url: "course_structure_check.php",
                    data: {s: subject, t: term, y: year, ms: msubject},
                    success: function(data) {
                        //alert(data);

                        if (data == '100')
                        {
                            $('#imgStructure').html("<img src='pic/OK.gif'></img>");
                            $('#planStr').html("<img src='pic/OK.gif'></img>");

                            $('#s2').val("true");
                            displeSendSubmit();

                        } else {
                            $('#imgStructure').html("<img src='pic/Cancel.gif'></img>");
                            $('#planStr').html("<img src='pic/Cancel.gif'></img>");
                            $('#s2').val("false");
                            displeSendSubmit();
                        }
                    }
                });

            }
            function displeSendSubmit() {
                //alert($('#s2').val());
//                //alert($('#s1').val());
//                var dis = //'<? //= $disble                                                                                                                     ?>'; //GET DISBLE WHEN SET NOT EDIT STRURCTER.
//                if ($('#s2').val() == 'true' && $('#s1').val() == '1' && dis == "") {
//                    document.getElementById('sendButton').disabled = false;
//                }
//                else {
//                    document.getElementById('sendButton').disabled = true;
//                }
            }
            function notuse() {
                $("#show_detail").show();
                $("#useStruture").hide();
            }
        </script>
        <!-- สิ้นสุดสคริป -->
    </head>
    <!--<body onload="checkCourseStrue();">-->
    <body>
        <?php include '../mainsystem/inc_head.php'; ?>
        <?php include '../mainsystem/inc_menu_point.php'; ?>
        <?php include '../mainsystem/inc_befordata.php'; ?>
        <!-- เริ่มข้อความ -->
        <center>
            <br/>
            <br/>  
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
                    <td><b>ปีการศึกษา</b>&nbsp;<span class="eng">(Year)</span>: <?= $_POST['id_issue']; ?></td>
                    <td></td>
                </tr>
            </table>  
            <br>
<?
if($objQuery)
{
	echo '<p><font color="Red">ทำการลบข้อมูลเรียบร้อยแแล้ว</font></p>';
	?>
    <form align="center" action="Edit_Delete_AllTest.php" method="POST" style="padding-top: 15px;">
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
  <?
}
else
{
	//echo "Error Delete [".$strSQL."]";
}

//echo"<script language=\"javascript\"> alert('ลบห้อมูลเรียบร้อยแแล้วค่ะ'); </script>";
//echo"<meta http-equiv='refresh' content='1;url=Edit_Delete_AllTest.php?id_course=$Subject'>";
?>

