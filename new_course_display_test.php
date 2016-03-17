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
echo $logper_type18r = $_SESSION["logper_type18r"];
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
$subone_name = "การจัดการข้อสอบ (Test Management)"; //หัวข้อหลัก
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
           
            <h2 align="right">
            
             <? if ($_SESSION["logper_type18r"] == 2) { ?> <a href="create_new_test.php">สร้างข้อสอบฉบับใหม่</a> <? } ?>

            </h2>
            <h2>โครงสร้างเนื้อหาการเรียนรู้ <span class="eng">( Content Structure )</span></h2>  
            <br/>
            <br/>
            <?
            if (empty($msubject)) {
                echo "<br/><br/><br/><br/><br/><br/><b><font color='red'>ระบบตรวจสอบว่าท่านยังไม่มีกลุ่มสาระ กรุณาติดต่อหัวหน้ากลุ่มสาระของท่าน</font></b><br/><br/><br/><br/><br/><br/><br/>";
            } else {
                ?>
                <form action="<?= $PHP_SELF ?>"  method="POST">
                    <table>
                        <tr>
                            <td>ปีการศึกษา <span class="eng">( Acadamic year )</span></td>
                            <td>
                                <select style="width: 170px;" name="year">
                                    <option value="" >-- เลือกปีการศึกษา ( Year ) --</option>
                                    <!--<option value="<?= date('Y') + 542; ?>"><?= date('Y') + 542; ?></option> -->
                                    <option value="<?= date('Y') + 542; ?>"><?= date('Y') + 542; ?></option> 
                                </select>
                            </td>
                        </tr>
                        <tr>   
                            <td>ภาคเรียน <span class="eng">( Semester )</span></td>
                            <td>
                                <select style="width: 170px;" name="term">
                                    <option value="">-- เลือกภาคเรียน ( Semester )--</option>
                                    <option value="ภาคเรียนที่ 1">ภาคเรียนที่ 1 ( Semester 1 )</option>
                                    <option value="ภาคเรียนที่ 2">ภาคเรียนที่ 2 ( Semester 2 )</option>
                                    <option value="ตลอดปี">ตลอดปี ( Round )</option>
                                </select>
                            </td>
                        </tr> 
                        <tr>
                            <td></td>
                            <td><input type="submit" name="search_name"  value=" Search "/></td>
                        </tr>
                    </table>
                </form>
                <br/>


                <table width="100%" style="border: 1px solid black; border-collapse: collapse;" bordercolor="#333333" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" width="10%" bgcolor="#DFECFF" height="35" style="border: 1px solid black;"><strong>รหัสวิชา</strong>
                            <br/>
                            <span class="eng">Code</span>
                        </td>
                        <td align="center" width="25%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>ชื่อวิชา</strong>
                            <br/>
                            <span class="eng">Subject</span>
                        </td>
                        <td align="center" width="10%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>ประเภทวิชา</strong>
                            <br/>
                            <span class="eng">Subject type</span>
                        </td>
                        <td align="center" width="10%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>หน่วยกิต</strong>
                            <span class="eng">Credit</span>
                        </td>
                        <td align="center" width="10%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>ช่วงชั้น</strong>
                            <span class="eng">Keystage</span>
                        </td>
                        <td align="center" width="10%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>ชั้นปี</strong>
                            <br/>
                            <span class="eng">Level</span>
                        </td>
                        <td align="center" width="10%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>คาบ/ภาค</strong>
                            <br/>
                            <span class="eng">Period/Semester</span>
                        </td>
                        <td align="center" width="15%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>สถานะการแสดง</strong>
                            <br/>
                            <span class="eng">Status</span>
                        </td>
                    </tr>
                    <?
                    if (isset($_POST['search_name'])) {
                        $year = $_POST['year'];
                        $term = $_POST['term'];
                        echo "" . $_POST['term'] . " ปีการศึกษา " . $_POST['year'] . "<br/><br/>";
                        if ($_POST['term'] == 'ตลอดปี') {
                            $sql_subject = "select * from sub_teacher inner join subject on(sub_teacher.subjectID=subject.subjectID) ";
                            $sql_subject .= " where sub_teacher.ECODE ='$login_ecode' and sub_teacher.TERM ='$term' and ";
                            $sql_subject .= " subject.MYEAR ='$year' and sub_teacher.MYEAR = subject.MYEAR and  subject.processed ='0'  GROUP BY  ";
                            $sql_subject .= " sub_teacher.sub_code,sub_teacher.ECODE ";
//                            $sql_subject = "select * from sub_teacher inner join subject on(sub_teacher.subjectID=subject.subjectID) where sub_teacher.ECODE ='$login_ecode' and sub_teacher.TERM ='$term' and subject.MYEAR ='$year' and sub_teacher.MYEAR =subject.MYEAR  GROUP BY  sub_teacher.sub_code,sub_teacher.ECODE";
                        } else {
                            //summer course  'ท32101', 'อ33201'   
                            if ($_POST['year'] == '2557' && $_POST['term'] == 'ภาคเรียนที่ 1') {
                                $array_summer_subject = "(
'ค30219',
'ค31101',
'ค32101',
'ค33201',
'ง30201',
'ง30211',
'ง31201',
'ท31103',
'ท32103',
'ท33103',
'ว31101',
'ว31102',
'ว31103',
'ว32101',
'ว32201',
'ว32221',
'ว32241',
'ว33201',
'ว33221',
'ว33241',
'ว33263',
'ส31101',
'ส32101',
'ส33201',
'อ30251',
'อ30252',
'อ30253',
'อ31101',
'อ32103',
'อ33101'           
)";
                                $sql_subject = "select * from sub_teacher inner join subject on(sub_teacher.subjectID=subject.subjectID)
                                    where sub_teacher.ECODE ='$login_ecode' and sub_teacher.TERM ='$term' and subject.MYEAR ='$year' and 
                                    sub_teacher.MYEAR =subject.MYEAR and subject.processed ='0' and subject.SCODE not in $array_summer_subject  
                                    GROUP BY  sub_teacher.sub_code,sub_teacher.ECODE";
                            } else {
                                $sql_subject = "select * from sub_teacher inner join subject 
                                on(sub_teacher.subjectID=subject.subjectID) where sub_teacher.ECODE ='$login_ecode' 
                                and sub_teacher.TERM ='$term' and subject.MYEAR ='$year' and  subject.term ='$term' 
                                and subject.processed ='0'    
                                and sub_teacher.MYEAR =subject.MYEAR     GROUP BY  sub_teacher.sub_code,sub_teacher.ECODE";
                            }
                            // $sql_subject = "select * from sub_teacher inner join subject on(sub_teacher.SUB_CODE=subject.SCODE) where sub_teacher.ECODE ='$login_ecode' and sub_teacher.TERM ='$term' and subject.MYEAR ='$year' and sub_teacher.MYEAR =subject.MYEAR  GROUP BY  sub_teacher.sub_code,sub_teacher.ECODE";
                        }
//                        echo $sql_subject;
                        $query_subject = mysql_query($sql_subject) or die('sql error');
                        if (mysql_num_rows($query_subject) <> 0) {
                            while ($data_subject = mysql_fetch_array($query_subject)) {
//                                $sql_ver = "SELECT * FROM `course_verify` WHERE   `verify_level` = '$type_level' and    course_id ='$data_subject[0]' and term='$term' and year ='$year' ";

                                $sql_ver = "SELECT * FROM `course_verify` WHERE  course_id ='$data_subject[0]' and term='$term' and year ='$year' ";

                                $query_ver = mysql_query($sql_ver) or die('error');
                                $data_ver = mysql_fetch_array($query_ver);
                                ?>
                                <tr>
                                    <td align="center" width="10%" height="25" style="border: 1px solid black;">  
                                        <!-- ส่งข้อมูล -->
                                        <form action="new_course_display_detail_test.php" method="POST" style="padding-top: 15px;">
                                            <input type="hidden" name="subject_id" value="<?= $data_subject['subjectID'] ?>" />
                                            <input type="hidden" name="subject" value="<?= $data_subject[0] ?>"/>
                                            <input type="hidden" name="subject_name" value="<?= $data_subject['SNAME'] ?>"/>
                                            <input type="hidden" name="unit" value="<?= $data_subject['UNIT'] ?>"/>
                                            <input type="hidden" name="term" value="<?= $term ?>"/>
                                            <input type="hidden" name="year" value="<?= $year ?>"/>
                                            <input type="hidden" name="classroom" value="<?= $data_subject['CCLASS'] . "," . $data_subject['CLASS'] ?>"/>
                                            <input type="hidden" name="tname" value="<?= $data_subject['TSNAME'] ?>"/> 
                                            <input type="submit" value="<?= $data_subject[0]; ?>" style="border: none;background: none;color: #2371E2;cursor: pointer;"/>
                                        </form> 
                                    </td>
                                    <td width="25%" height="20" style="border: 1px solid black;padding-left: 5px;padding-right: 5px;" class="paddingLeftTable"><?= $data_subject['SNAME']; ?></td>
                                    <td width="10%" style="border: 1px solid black;" align="center"><?= $data_subject['TSNAME']; ?></td>
                                    <td width="10%" style="border: 1px solid black;" align="center"><?= $data_subject['UNIT']; ?></td>
                                    <td width="10%" style="border: 1px solid black;" align="center"><?= $data_subject['CCLASS']; ?></td>
                                    <td width="10%" style="border: 1px solid black;" align="center"><?= $data_subject['CLASS']; ?></td>
                                    <td width="10%" align="center" style="border: 1px solid black;">
                                        <?
                                        if ($term == 'ตลอดปี') {
                                            echo "40";
                                        } else {
                                            echo "20";
                                        }
                                        ?>
                                    </td>
                                    <td align="center" width="15%" style="border: 1px solid black;"><?= verityCourse($data_ver['result'], $data_ver['detail']); ?></td>
                                </tr>
                                <?
                            }
                        } else {
                            ?>
                            <tr>
                                <td style="color: red;" colspan="8" height="30" align="center">ไม่พบข้อมูลที่ค้นหา กรุณาตรวจสอบข้อมูลการค้นหาอีกครั้งค่ะ</td>
                            </tr>
                            <?
                        }
                    }
                    ?>
                </table> 
            <? } ?>
            <?

            //function status cousr verify
            function verityCourse($value, $msg) {
                $status = '';
                if ($value == 0) {
                    $status = '<font color="red">Structure has not been set </font>';
                } else if ($value == 1) {
                    $status = '<font color="green">Approved </font>';
                } else if ($value == 2) {
                    $status = "<a href=javascript:alertMsg('$msg'); style='color:red;'>Not approved<br/>Click</a>";
                } else if ($value == 3) {
                    $status = '<font color="blue">Need approval</font>';
                }
                return $status;
            }
            ?>
            <script type="text/javascript">
                function alertMsg(msg) {
                    alert(msg);
                }
            </script>

        </center>
        <!-- สิ้นสุดข้อความ -->
        <?php include '../mainsystem/inc_afterdata.php'; ?>
        <?php include '../mainsystem/inc_footer3.php'; ?>
        <?php include '../mainsystem/inc_footer2.php'; ?>
    </body>
</html>
<?php include '../mainsystem/inc_endpage.php'; ?>
<?php
//mysql_free_result($rsnews);
//mysql_free_result(rscat);
mysql_close();
//
?>

