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
$subjectnamedb = $_POST['subject_name'];
$subjectIDdb = $_POST['subject_id'];
$typedb = $_POST['type'];
$score = $_POST['score'];
$level = $_POST['level'];
$time = date('Y-m-d H:i:s');
$subjectdb0 = substr($subjectdb, -4);
$subjectdb1 = substr($subjectdb, 0,8);
$subjectdb2 = substr($subjectdb, 8);
$subjectdb3 = substr($subjectdb2, 0,-4);
//echo $subjectdb3;
mysql_query("Set names 'utf8'");
 $sql = "INSERT INTO  Issue_question (Id_Issue,subjectID,Time,id_course,name_course,type,yeardb,creater) VALUES ('','$subjectIDdb','$time','$subjectdb','$subjectnamedb','$typedb','$yeardb','$creater')";
//echo $sql;
mysql_query($sql);
//header( "refresh:1;url=new_course_display_detail_test.php" );


?>
<?
mysql_select_db($database_bmks, $bmks);
require_once 'subject.php';
require_once 'encodeUrl/encodeUrl.php';
mysql_select_db($database_bmksl, $bmks);

// บันทึก Log ----
//$myip = $_SERVER['REMOTE_ADDR'];
//$query_neoxe = "INSERT INTO savelog(ECODE,savelog_type,savelog_ip) VALUES('$login_ecode','6','$myip')"; //6 จัดการตัวชี้วัด
//$neoxe = mysql_query($query_neoxe, $bmks) or die(mysql_error());
////-----------------

$charset = "utf-8";
$mime = "text/html";
header("Content-Type: $mime;charset=$charset");
?>
<!-- สิ้นสุดหัว -->
<?php include '../mainsystem/inc_startpage.php'; ?>
<?
$maintitile_name = "ระบบบันทึกผลการเรียน"; //ชื่อโปรแกรม และหัวเว็บ
$subtitile_name = "Assessment Record System"; //คำอธิบายโปรแกรม
$subone_name = "การจัดการข้อสอบ  ( Test management )"; //หัวข้อหลัก
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
        <style type="text/css">
    .textAlignVer{  
        display:block;  
        writing-mode: tb-rl;  
        filter: flipv fliph;  
        -webkit-transform: rotate(-90deg);   
        -moz-transform: rotate(-90deg);   
        transform: rotate(-90deg);   
        position:relative;  
        width:20px;  
        white-space:nowrap;  
        font-size:12px;  
        margin-bottom:10px;  
}
        </style>
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
                    <td><b>ปีการศึกษา</b>&nbsp;<span class="eng">(Year)</span>: <?= $_POST['year']; ?></td>
                    <td></td>
                </tr>
            </table>   
            <br/>
            <br/>

            <?
    $sql1 = "SELECT * from Issue_question ORDER BY Id_Issue DESC LIMIT 1 ";
    $objquery = mysql_query($sql1);
    ?>
    <table border="1">
    <th>รหัสวิชา</th>
    <th>ชื่อวิชา</th>
    <th>ชนิดข้อสอบ</th>
    <th>เวลาที่สร้าง</th>
    <th>ผู้สร้าง</th>
    <?
    
    while ($objResult = mysql_fetch_array($objquery)) { ?>
                                <tr>
                                    <td align="center" width="10%" style="border: 1px solid black;padding-left: 5px;padding-right: 5px;" class="paddingLeftTable"><?=$objResult['id_course'];?></td>
                                    <td width="20%" style="border: 1px solid black;" align="center"><? echo $objResult['name_course'] ;?></td>
                                    <td width="10%" style="border: 1px solid black;" align="center"><?=$objResult['type'];?></td>
                                   
                                    <td width="10%" style="border: 1px solid black;" align="center"><?=$objResult['Time'];?></td>
                                    <td width="10%" align="center" style="border: 1px solid black;"><?=$objResult['creater'];?></td>
                                    
                                    
                                </tr>
                             <? 

             
                         }
                             ?>
  </table>
  <table cellspacing="15">
        <!--                <tr>
                            <td><img src="pic/OK.gif"></img></td>
                            <td><a href="attribute_of_school_course.php?subject=<?= $_POST['subject']; ?>&&subject_name=<?= $_POST['subject_name']; ?>&&unit=<?= $_POST['unit']; ?>&&term=<?= $_POST['term']; ?>&&year=<?= $_POST['year']; ?>&&classRoom=<?= $_POST['classRoom']; ?>">คุณลักษณะอันพึงประสงค์ของโรงเรียน <span class="eng">( School desirable characteristics )</span></a></td>
                        </tr>-->
                         
                        
                            
                            <td align="left">
                                <form action="new_course_display_detail_test.php" method="POST" style="padding-top: 15px;">
                                    <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    <input type="submit" value="กลับสู่หน้าหลัก  ( Back )  " style="border: none;background: none;color: #2371E2;cursor: pointer;"/>
                                </form> 
                                </td>
                              
                                <!--<a href="new_course_structure.php?subject=<?= $_POST['subject']; ?>&&subject_name=<?= $_POST['subject_name']; ?>&&unit=<?= $_POST['unit']; ?>&&term=<?= $_POST['term']; ?>&&year=<?= $_POST['year']; ?>&&classRoom=<?= $_POST['classRoom']; ?>">จัดการโครงสร้างเเนื้อหา <span class="eng"> ( Management of content structure ) </span></a></td>-->
                        </tr>
                       
<!--                <tr>
                    <td ><span id="planStr"></span></img></td>
                    <td align="left"><a href="course_structure_plan.php?subject=<?= $_POST['subject']; ?>&&subject_name=<?= $_POST['subject_name']; ?>&&unit=<?= $_POST['unit']; ?>&&term=<?= $_POST['term']; ?>&&year=<?= $_POST['year']; ?>&&classRoom=<?= $_POST['classRoom']; ?>">เเผนการประเมินผลการเรียน <span class="eng"> ( Evaluation plan ) </span></a></td>
                </tr>-->

                    </table>