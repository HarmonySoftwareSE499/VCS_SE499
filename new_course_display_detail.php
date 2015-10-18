<?php
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
$subone_name = "โครงสร้างเนื้อหาการเรียนรู้ (Content Study)"; //หัวข้อหลัก
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
            //find structrue this year 
            $sql_new_course_struture = "select * from evaulation.new_course_struture where ";
            $sql_new_course_struture .= " evaulation.new_course_struture.id_course = '" . $_POST['subject_id'] . "'";
            $query_new_course_struture = mysql_query($sql_new_course_struture) or die(mysql_error());
            $status_hide = false;
            if (mysql_num_rows($query_new_course_struture) == 0) {
                ?>

                <?php
                //find data this similar this structure 
                $sql_find_course_before_data = "select * from evaulation.subject 
                    inner join evaulation.new_course_struture
                    on evaulation.new_course_struture.id_course = evaulation.subject.subjectID 
                    where evaulation.subject.SCODE ='" . $_POST['subject'] . "' and evaulation.subject.myear < '" . $_POST['year'] . "' order by evaulation.subject.subjectID desc limit 0,1 ";
                $query_find_course_before_data = mysql_query($sql_find_course_before_data) or die(mysql_error());
                $num_find_course_before_data = mysql_num_rows($query_find_course_before_data);
                if ($num_find_course_before_data <> 0) {
                    ?>
                    <div style="background: #eee;border:1px #ddd solid;" id="useStruture">
                        <?
                        $status_hide = true;
                        echo "<h1>***ระบบทำการค้นหาพบวิชาที่มีโครงสร้างเดียวกันกับบวิชานี้ <br/>ท่านต้องการใช้โครงสร้างวิชานี้หรือไม่</h1>";
                        ?>
                        <br/>
                        <form action="new_course_display_detail_transfer_data.php" method="POST">
                            <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                            <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                            <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                            <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                            <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                            <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                            <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                            <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                            <input type="submit" value=" ต้องการ " name="transferData"/>
                            <input type="button" value=" ไม่ต้องการ " onclick="notuse();"/>
                        </form>
                        <br/>
                        <br/>
                    </div>
                    <?
                }
            }
            ?>
            <br/>
            <?
            if ($status_hide) {
                ?>
                <div id="show_detail" style="display: none;">
                    <?
                } else {
                    ?>
                    <div id="show_detail">
                        <?
                    }
                    ?>
                    <table cellspacing="15">
        <!--                <tr>
                            <td><img src="pic/OK.gif"></img></td>
                            <td><a href="attribute_of_school_course.php?subject=<?= $_POST['subject']; ?>&&subject_name=<?= $_POST['subject_name']; ?>&&unit=<?= $_POST['unit']; ?>&&term=<?= $_POST['term']; ?>&&year=<?= $_POST['year']; ?>&&classRoom=<?= $_POST['classRoom']; ?>">คุณลักษณะอันพึงประสงค์ของโรงเรียน <span class="eng">( School desirable characteristics )</span></a></td>
                        </tr>-->
                        <tr>

                            <?
                            $sql_check_status = "select * from course_description where subjectID='" . $_POST['subject_id'] . "' and term='" . $_POST['term'] . "' and year ='" . $_POST['year'] . "'";
                            $query_check_s = mysql_query($sql_check_status) or die('error');
                            $num_rows = mysql_num_rows($query_check_s);
                            if ($num_rows <> 0) {
                                ?><td> 
                                    <?
                                    echo '<img src="pic/OK.gif"></img>';
                                    $s1 = true;
                                    ?>
                                </td>
                                <td align="left">
                                    <form action="new_course_detail.php" method="POST" style="padding-top: 15px;"  id="detailFrm">
                                        <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                        <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                        <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                        <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                        <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                        <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                        <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                        <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    </form>
                                    <a  href="javascript:document.getElementById('detailFrm').submit();">คำอธิบายรายวิชา <span class="eng"> ( Subject description ) </span></a></td>
                                <?
                            } else {
                                ?><td>
                                        <?
                                        echo '<img src="pic/Cancel.gif">';
                                        $s1 = false;
                                        ?>
                                </td> 
                                <td align="left">
                                    <form action="new_course_detail.php" method="POST" style="padding-top: 15px;"  id="detailFrm2">
                                        <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                        <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                        <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                        <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                        <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                        <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                        <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                        <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    </form>
                                    <a href="javascript:document.getElementById('detailFrm2').submit();">คำอธิบายรายวิชา <span class="eng"> ( Subject description ) </span></a>
                                </td>
                                <?
                            }
                            ?>
                        </tr>  
                        <tr>
                            <td ><span id="imgStructure"></span></td>
                            <td align="left">
                                <form action="new_course_structure.php" method="POST" style="padding-top: 15px;">
                                    <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    <input type="submit" value="จัดการโครงสร้างเเนื้อหา  ( Management of content structure )  " style="border: none;background: none;color: #2371E2;cursor: pointer;"/>
                                </form> 
                                <!--<a href="new_course_structure.php?subject=<?= $_POST['subject']; ?>&&subject_name=<?= $_POST['subject_name']; ?>&&unit=<?= $_POST['unit']; ?>&&term=<?= $_POST['term']; ?>&&year=<?= $_POST['year']; ?>&&classRoom=<?= $_POST['classRoom']; ?>">จัดการโครงสร้างเเนื้อหา <span class="eng"> ( Management of content structure ) </span></a></td>-->
                        </tr>
                        <tr>
                            <td ><span id="imgStructure"></span></td>
                            <td align="left">
                                <form action="new_test.php" method="POST" style="padding-top: 15px;">
                                    <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    <input type="submit" value="การจัดการข้อสอบ  ( Test management )  " style="border: none;background: none;color: #2371E2;cursor: pointer;"/>
                                </form> 
                                <!--<a href="new_course_structure.php?subject=<?= $_POST['subject']; ?>&&subject_name=<?= $_POST['subject_name']; ?>&&unit=<?= $_POST['unit']; ?>&&term=<?= $_POST['term']; ?>&&year=<?= $_POST['year']; ?>&&classRoom=<?= $_POST['classRoom']; ?>">จัดการโครงสร้างเเนื้อหา <span class="eng"> ( Management of content structure ) </span></a></td>-->
                        </tr>

<!--                <tr>
                    <td ><span id="planStr"></span></img></td>
                    <td align="left"><a href="course_structure_plan.php?subject=<?= $_POST['subject']; ?>&&subject_name=<?= $_POST['subject_name']; ?>&&unit=<?= $_POST['unit']; ?>&&term=<?= $_POST['term']; ?>&&year=<?= $_POST['year']; ?>&&classRoom=<?= $_POST['classRoom']; ?>">เเผนการประเมินผลการเรียน <span class="eng"> ( Evaluation plan ) </span></a></td>
                </tr>-->

                    </table>
                    <?
                    if (decodeUrl($_POST['term']) == 'ตลอดปี') {
                        $courseIdName = str_split(trim(decodeUrl($_POST['subject'])));
                        $str_course = "";
                        $array_str_course = array("ท", "ค", "ว", "ส", "พ", "ศ", "ง", "จ", "อ"
                            , "TH", "MA", "SC", "SO", "HP", "AR", "OT", "CH", "EN");
                        foreach ($courseIdName as $keysp) {

                            if (!is_numeric($keysp)) {
                                //echo $keysp . "<br/>";
                                $str_course .=$keysp;
                            }
                        }
                        foreach ($array_str_course as $idCourseStr) {
                            if ($idCourseStr == $str_course) {
                                if ($str_course == 'ท' || $str_course == 'TH') {
                                    $msubject = "ท";
                                    break;
                                } else if ($str_course == 'ค' || $str_course == 'MA') {
                                    $msubject = "ค";
                                    break;
                                } else if ($str_course == 'ว' || $str_course == 'SC') {
                                    $msubject = "ว";
                                    break;
                                } else if ($str_course == 'ส' || $str_course == 'SO') {
                                    $msubject = "ส";
                                    break;
                                } else if ($str_course == 'พ' || $str_course == 'HP') {
                                    $msubject = "พ";
                                    break;
                                } else if ($str_course == 'ศ' || $str_course == 'AR') {
                                    $msubject = "ศ";
                                    break;
                                } else if ($str_course == 'ง' || $str_course == 'AR') {
                                    $msubject = "ง";
                                    break;
                                } else if ($str_course == 'จ' || $str_course == 'CH') {
                                    $msubject = "จ";
                                    break;
                                } else if ($str_course == 'อ' || $str_course == 'EN') {
                                    $msubject = "อ";
                                    break;
                                }
                            }
                        }
                    }
                    ?>
                    <p>
                        <script type="text/javascript">
            function sendDataApp() {
                document.getElementById("formappoves2").submit();
            }
                        </script>
                        <form action="coures_verify/new_insertVerify.php" method="POST" id="formappoves2"> 
                            <?
                            $gg_text = mb_substr(trim($_POST['subject']), 0, 1, 'UTF-8');
                            if ($arrClass[0] == "ช่วงชั้นที่ 3" || $arrClass[0] == "ช่วงชั้นที่ 4") {
                                $verify_level = '1';
                            } else {
                                $verify_level = '0';
                            }
                            ?>
                            <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>" />
                            <input type="hidden" name="term" value="<?= $_POST['term']; ?>" />
                            <input type="hidden" name="year" value="<?= $_POST['year']; ?>" /> 
                            <input type="hidden" name="verify_group" value="<?= $gg_text; ?>" /> 
                            <input type="hidden" name="verify_level" value="<?= $verify_level; ?>" />  
                            <br/>
                            <br/>
                            <input type="hidden" name="s1" id="s1" value="<?= $s1; ?>" /> 
                            <input type="hidden" name="s2" id="s2" /> 
                        </form>
                        <? if (empty($disble)) { ?>
                            <input type="button" value=" Approved " onclick="sendDataApp();"/>&nbsp; 
                        <? } ?>
                        <input type="button" value=" Back " onclick="$('#backUrl').submit();"/>

                        <form action="new_course_display.php" method="POST" id="backUrl"> 
                            <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>" />
                            <input type="hidden" name="term" value="<?= $_POST['term']; ?>" />
                            <input type="hidden" name="year" value="<?= $_POST['year']; ?>" /> 
                            <input type="hidden" name="search_name"/> 
                        </form>
                </div>

                </p>
        </center>
        <br/>
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
?>