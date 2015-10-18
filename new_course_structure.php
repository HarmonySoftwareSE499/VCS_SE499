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

// บันทึก Log ----
mysql_select_db($database_bmks, $bmks);
include 'subject.php';
require_once 'encodeUrl/encodeUrl.php';
$charset = "utf-8";
$mime = "text/html";
//header("Content-Type: $mime;charset=$charset");
mysql_select_db($database_bmksl, $bmksl);
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
        <style type="text/css"> 
            .paddingLeft{
                padding-left: 5px;
                padding-right: 5px;
            } 
        </style>
        <script type="text/javascript" src="cssAutocomplete/jquery-1.9.0.js"></script>
        <script>
            function k_week_del(id_del)
            {
                var con = confirm('คุณต้องการลบเนื้อหาการเรียนรู้สัปดาห์นี้หรือไม่');
                var url = "course_structure/delete_course_structure.php?subject=<?= $_POST['subject']; ?>&&subject_name=<?= $_POST['subject_name']; ?>&&unit=<?= $_POST['unit']; ?>&&term=<?= $_POST['term']; ?>&&year=<?= $_POST['year']; ?>&&s1=<?= $s1; ?>&&classRoom=<?= $_POST['classRoom']; ?>&&idDel=" + id_del;
                if (con == true)
                {
                    window.location.replace(url);
                }
            }
        </script> 
        <script>
            function selectKarma(id)
            {
                if (sumAllCal() <= 20) {
                    if (document.getElementById('id_' + id).checked) {
                        var value = prompt("กรุณาใสช่องคะเเนน", "0");
                        if (value == null) {
                            document.getElementById('id_' + id).checked = false;
                        }
                        else if (isNaN(value))
                        {
                            //alert("กรุณากรอกคะเเนนที่เป็นตัวเลขด้วยค่ะ");
                            document.getElementById("validate").innerHTML = "กรุณากรอกคะเเนนที่เป็นตัวเลขด้วยค่ะ";
                            document.getElementById('value_' + id).value = 0;
                            document.getElementById('id_' + id).checked = false;
                        }
                        else
                        {
                            var numberA = Number(value);
                            if (numberA <= 0) {
                                //alert("กรุณากรอกคะเเนนที่มากกว่า 0 ด้วยค่ะ");
                                document.getElementById("validate").innerHTML = "กรุณากรอกคะเเนนที่มากกว่า 0 ด้วยค่ะ";
                                document.getElementById('id_' + id).checked = false;
                            }
                            else {
                                if (numberA <= 20 && sumValuNow(numberA) <= 20) {
                                    document.getElementById("validate").innerHTML = "";
                                    document.getElementById('value_' + id).value = numberA;
                                    document.getElementById('point').innerHTML = sumAllCal();
                                    document.getElementById('bg_' + id).style.background = '#dddddd';
                                    //send data in cousr_karma
                                    sendDataInsert('karma_corse/karma_course_insert.php', id);
                                } else
                                {
                                    document.getElementById("validate").innerHTML = "กรุณาตรวจคะเเนนไม่ควรเกิน 20 ค่ะ";
                                    document.getElementById('id_' + id).checked = false;
                                }
                            }
                        }
                    }
                    else {
                        document.getElementById('value_' + id).value = 0;
                        document.getElementById('point').innerHTML = sumAllCal();
                        document.getElementById('bg_' + id).style.background = '#ffffff';
                        sendDataInsert("karma_corse/karma_course_deleted.php", id)
                        //send data in cousr_karma
                    }
                }
                else {
                    document.getElementById("validate").innerHTML = "กรุณาตรวจคะเเนนไม่ควรเกิน 10 ค่ะ";
                }
            }
            function sumAllCal() {
                //size max
                var max = document.getElementById('sizeMax').value;
                var sumAll = 0;
                for (var i = 1; i < max; i++) {
                    sumAll += Number(document.getElementById('value_' + i).value);
                }
                return sumAll;
            }
            function sumValuNow(valueNow)
            {
                var max = document.getElementById('sizeMax').value;
                var sumAll = 0;
                for (var i = 1; i < max; i++) {
                    sumAll += Number(document.getElementById('value_' + i).value);
                }
                return sumAll + valueNow;
            }
            function sendDataInsert(url, id)
            {
                var c = '<?= $_POST['subject']; ?>';
                var t = '<?= $_POST['term']; ?>';
                var y = '<?= $_POST['year']; ?>';
                var i = document.getElementById("karma_" + id).value;
                var s = document.getElementById("value_" + id).value;
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {course: c, term: t, year: y, idkarma: i, score: s},
                    success: function(data) {
                        window.location.reload('course_structure.php?subject=<?= $_POST['subject']; ?>&&subject_name=<?= $_POST['subject_name']; ?>&&unit=<?= $_POST['unit']; ?>&&term=<?= $_POST['term']; ?>&&year=<?= $_POST['year']; ?>&&classRoom=<?= $_POST['classRoom']; ?>');
                    }
                });
            }
            function editIndicator(idEdit) {
                var url = "course_structure_edit.php?subject=<?= $_POST['subject']; ?>&&subject_name=<?= $_POST['subject_name']; ?>&&unit=<?= $_POST['unit']; ?>&&term=<?= $_POST['term']; ?>&&year=<?= $_POST['year']; ?>&&s1=<?= $s1; ?>&&classRoom=<?= $_POST['classRoom']; ?>&&idEdit=" + idEdit;
                window.location.replace(url);
            }
            //FUNCTION PRINT STRUCTER COURSE 
        </script>   
        <!-- สิ้นสุดสคริป -->
    </head>
    <body>
        <?php include '../mainsystem/inc_head.php'; ?>
        <?php include '../mainsystem/inc_menu_point.php'; ?>
        <?php include '../mainsystem/inc_befordata.php'; ?>
        <!-- เริ่มข้อความ -->
        <div style="float: right;">
            <form action="pdf/examples/new_report_struture_print.php" method="POST" target="_blank">
                <!--VALUE POST DATA-->
                <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                <input type="hidden" name="subject" value="<?= $_POST['subject'] ?>"/>
                <input type="hidden" name="subject_name" value="<?= $_POST['subject_name'] ?>"/>
                <input type="hidden" name="unit" value="<?= $_POST['unit'] ?>"/>
                <input type="hidden" name="term" value="<?= $_POST['term'] ?>"/>
                <input type="hidden" name="year" value="<?= $_POST['year'] ?>"/>
                <input type="hidden" name="classRoom" value="<?= $_POST['classRoom'] ?>"/> 
                <input type="hidden" name="typeCourse" value="<?= $_SESSION['tnameCourse']; ?>"/> 
                <input type="hidden" name="pL" value="<?= $data_dep['p']; ?>"/>  
                <input type="hidden" name="sL" value="<?= $data_dep['s']; ?>"/> 
                <input type="hidden" name="g" value="<?= $msubject ?>" />
                <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                <input type="submit" value="  Print Subject Content "  />
                <!--END POST DATA-->
            </form>
        </div>
        <div style="clear: both;" />

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
        <?

        function find_indicator_val($id_indicator) {
            $sql_find_indicator = "select * from evaulation.indicators where evaulation.indicators.idindicators ='$id_indicator'";
            $query_find_indicator = mysql_query($sql_find_indicator) or die(mysql_error());
            $row_data_find_indicator = mysql_fetch_array($query_find_indicator);
            return $row_data_find_indicator['indicatorscol_name'] . "<br/>(" . $row_data_find_indicator['indicatorscol_code'] . ")&nbsp;";
        }

        function cal_score_indicator($percent, $score_all) {
            return number_format($percent * $score_all / 100, 2);
        }
        ?>
        <? if (empty($disble)) { ?>
            <table>
                <tr>
                    <td>
                        <form action="new_course_structure_add.php" method="POST" style="padding-top: 15px;">
                            <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                            <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                            <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                            <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                            <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                            <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                            <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                            <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                            <input type="submit" value=" เพิ่มหน่วยการเรียนรู้ /Add Unit Of Content " style="border: none;background: none;color: #2371E2;cursor: pointer;"/>
                        </form> 
                    </td>
                    <?
                    $arr_Class = explode(",", $_POST['classroom']);
//                                            echo $arr_Class[0];
                    if ($arr_Class[0] == "ช่วงชั้นที่ 4" || $arr_Class[0] == "ช่วงชั้นที่ 3") {
                        if ($_SESSION['tnameCourse'] == "วิชาเพิ่มเติม") {
                            ?>
                            <td>
                                <form action="new_course_structure_add_indicator.php" method="POST" style="padding-top: 15px;">
                                    <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    <input type="submit" value=" จัดการผลการเรียนรู้ /Manage Assessment" style="border: none;background: none;color: #2371E2;cursor: pointer;"/>
                                </form> 
                            </td>
                            
                            <?
                        }
                    }
                    ?>
                </tr>
            </table>


                                                                                                                        <!--<a href="course_structure_add.php?subject=<?= $_POST['subject']; ?>&&subject_name=<?= $_POST['subject_name']; ?>&&unit=<?= $_POST['unit']; ?>&&term=<?= $_POST['term']; ?>&&year=<?= $_POST['year']; ?>&&s1=<?= $s1; ?>&&classRoom=<?= $_POST['classRoom']; ?>"><img src="pic/Add.gif" border="0"></img>&nbsp;เพิ่มเนื้อหาการเรียนรู้</a> <br />-->
        <? } else {
            ?>
            <br/>
            <br/>
            <? }
        ?> 
        <?
        if (isset($_POST['del'])) {
            //update new_course_struture
            $sql_update_new_course_struture = "update evaulation.new_course_struture set evaulation.new_course_struture.delete='1'   where evaulation.new_course_struture.start_w ='" . $_POST['del_start_w'] . "'";
            $sql_update_new_course_struture .= " and evaulation.new_course_struture.id_course='" . $_POST['subject_id'] . "'";
            mysql_query($sql_update_new_course_struture) or die(mysql_error());

            //update new_evaulation_text
            $sql_update_new_evaulation_text = "update evaulation.new_evaulation_text set evaulation.new_evaulation_text.delete='1'";
            $sql_update_new_evaulation_text .=" where evaulation.new_evaulation_text.start_w = '" . $_POST['del_start_w'] . "'";
            $sql_update_new_evaulation_text .=" and evaulation.new_evaulation_text.id_course = '" . $_POST['subject_id'] . "'";
            mysql_query($sql_update_new_evaulation_text) or die(mysql_error());
        }
        ?>
        <!-- ตารางโครงสร้างวิชา -->
        <table width="100%" cellspacing="0" cellpadding="0" bordercolor="#333333" align="center" style="border: 1px solid black;border-collapse: collapse;">
            <tr>
                <td width="10%" align="center" bgcolor="#DFECFF" style="border: 1px solid black;">
                    <b>สัปดาห์ที่<br/>
                        Week</b>
                </td>
                <td width="15%" align="center" bgcolor="#DFECFF" style="border: 1px solid black;">
                    <b>หน่วยการเรียนรู้<br/>
                        Unit
                    </b>
                </td> 
                <td width="20%" align="center" bgcolor="#DFECFF" style="border: 1px solid black;">
                    <b>เนื้อหา
                        <br/>
                        Content
                    </b>
                </td> 
                <td width="25%" align="center" bgcolor="#DFECFF" style="border: 1px solid black;">
                    <b>ภาระงาน<br/>
                        Task
                    </b>
                </td>
                <td width="25%" align="center" bgcolor="#DFECFF" style="border: 1px solid black;">
                    <b>การวัดผลการเรียนรู้
                        <br/>Assessment
                    </b>
                </td>
                <td width="5%" align="center" bgcolor="#DFECFF" style="border: 1px solid black;">
                    <b>กระทำ
                        <br/>Action
                    </b>
                </td>
            </tr>
            <?PHP
            //varible
            $formative1 = 0;
            $test1 = 0;
            $formative2 = 0;
            $test2 = 0;
            $formative3 = 0;
            $test3 = 0;
            $formative4 = 0;
            $test4 = 0;
            $score1 = 0;
            $score2 = 0;
            $score3 = 0;
            $score4 = 0;

            $sql_find_struture_week = "select * from evaulation.new_course_struture where evaulation.new_course_struture.id_course ='" . $_POST['subject_id'] . "' and  evaulation.new_course_struture.delete = 0 order by evaulation.new_course_struture.start_w asc";
            $query_find_strutrue = mysql_query($sql_find_struture_week);
            while ($row_data_find_struture = mysql_fetch_array($query_find_strutrue)) {
                ?>
                <tr>
                    <td height="25" style="border: 1px solid black;" valign="top" align="center"><?= $row_data_find_struture['start_w'] . "-" . $row_data_find_struture['stop_w'] ?></td> 
                    <td valign="top" style="border: 1px solid black;text-align: justify;"><?= str_replace("\\", "", $row_data_find_struture['unit_course']) ?></td>
                    <td valign="top" style="border: 1px solid black;text-align: justify;"><?= str_replace("\\", "", preg_replace("/\r\n|\r/", "<br />", $row_data_find_struture['process_course'])) ?></td>
                    <td valign="top" style="border: 1px solid black;text-align: justify;"><?= str_replace("\\", "", preg_replace("/\r\n|\r/", "<br />", $row_data_find_struture['works'])) ?></td>
                    <td valign="top" style="border: 1px solid black;text-align: justify;">
                        <?PHP
                        $sql_evaulation_find = "select * from evaulation.new_evaulation_text where evaulation.new_evaulation_text.id_course ='" . $_POST['subject_id'] . "' and  evaulation.new_evaulation_text.start_w ='" . $row_data_find_struture['start_w'] . "'";
                        $sql_evaulation_find .=" and evaulation.new_evaulation_text.stop_w='" . $row_data_find_struture['stop_w'] . "'";
                        $sql_evaulation_find .= " and evaulation.new_evaulation_text.delete = 0 order by evaulation.new_evaulation_text.start_w asc";
                        $query_evaulation_find = mysql_query($sql_evaulation_find) or die(mysql_error());
                        $num_evaulation = 1;
                        while ($row_data_evaulation_find = mysql_fetch_array($query_evaulation_find)) {
                            if ($_POST['term'] == 'ตลอดปี') {

                                if ($row_data_find_struture['start_w'] < 10) {
                                    $formative1+= $row_data_evaulation_find['score'];
                                } else if ($row_data_find_struture['start_w'] == 10) {
                                    $test1+= $row_data_evaulation_find['score'];
                                } else if ($row_data_find_struture['start_w'] < 20) {
                                    $formative2+= $row_data_evaulation_find['score'];
                                } else if ($row_data_find_struture['start_w'] == 20) {
                                    $test2+= $row_data_evaulation_find['score'];
                                } else if ($row_data_find_struture['start_w'] < 30) {
                                    $formative3+= $row_data_evaulation_find['score'];
                                } else if ($row_data_find_struture['start_w'] == 30) {
                                    $test3+= $row_data_evaulation_find['score'];
                                } else if ($row_data_find_struture['start_w'] < 40) {
                                    $formative4+= $row_data_evaulation_find['score'];
                                } else if ($row_data_find_struture['start_w'] == 40) {
                                    $test4 += $row_data_evaulation_find['score'];
                                }
                            } else {
                                if ($row_data_find_struture['start_w'] < 10) {
                                    $score1 += $row_data_evaulation_find['score'];
                                } else if ($row_data_find_struture['start_w'] == 10) {
                                    $score2 += $row_data_evaulation_find['score'];
                                } else if ($row_data_find_struture['start_w'] < 20) {
                                    $score3 += $row_data_evaulation_find['score'];
                                } else if ($row_data_find_struture['start_w'] == 20) {
                                    $score4 += $row_data_evaulation_find['score'];
                                }
                            }
                            ?>
                            <table>
                                <tr>
                                    <td valign="top" style="padding: 0;"><?= $num_evaulation . "." ?></td>
                                    <td style="padding: 0;text-align: justify;"><b><?= $row_data_evaulation_find['title'] ?></b> ,<?= $row_data_evaulation_find['score'] ?> คะเเนน (Score)</td>
                                </tr>
                                <tr>
                                    <td style="padding: 0;"></td>
                                    <td style="text-align: justify;padding: 0;"> 
                                        <?
                                        $data_indicator_buffer = $row_data_evaulation_find['indicator'];
                                        $str_explode = explode("@", $data_indicator_buffer);
                                        $num_val = 1;
                                        ?>
                                        <table>
                                            <?
                                            foreach ($str_explode as $val) {
                                                if (!empty($val)) {
                                                    $val_ex = explode("--", $val);
                                                    ?>
                                                    <tr>
                                                        <td valign="top" style="padding: 0;"><?= $num_val . "." ?></td>
                                                        <td style="padding: 0;"><?= find_indicator_val($val_ex[0]) . "," . $val_ex[1] . " คะเเนน (Score)" ?></td>
                                                    </tr>
                                                    <?
                                                    $num_val++;
                                                }
                                            }
                                            ?>   
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            <br/>
                            <br/>
                            <?
                            $num_evaulation++;
                        }
                        ?>
                    </td>
                    <td valign="top" style="border: 1px solid black;padding-top: 10px;">
                        <? if (empty($disble)) { ?>
                            <table align="center">
                                <tr>
                                    <td>  
                                        <form action="new_course_struture_edit.php" method="post" >
                                            <!--- id edit --> 
                                            <input type="hidden" name="id_edit" value="<?= $row_data_find_struture['id_auto'] ?>" />

                                            <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                            <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                            <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                            <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                            <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                            <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                            <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                            <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                            <input type="submit" value=" " title=" เเก้ไข " style="background: url('pic/edit.png');cursor: pointer;"/>
                                        </form>
                                    </td>
                                    <td> 
                                        <form action="<?= $PHP_SELF ?>" method="post" onsubmit="return checkDel();" >
                                            <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                            <input type="hidden" name="del_start_w" value="<?= $row_data_find_struture['start_w']; ?>" /> 
                                            <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                            <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                            <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                            <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                            <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                            <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                            <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                            <input type="submit" value=" " name="del" title=" ลบ " style="background: url('pic/delete.gif');cursor: pointer;"/>
                                        </form>
                                    </td>
                                </tr> 
                            </table> 
                        <? } ?>
                    </td>
                </tr>
                <?
            }
            ?>
        </table>
        <!-- สินสุดตารางโครงสร้างรายวิชา-->
        <br/> 
        <br/>
        <br/> 
        <? if (empty($disble)) { ?>
            <form action="new_course_structure_add_project.php" method="post">
                <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                <input type="submit" value=" Project/จิตพิสัย " style="border:none;background: none;color: blue;cursor: pointer;"/>
            </form> 
        <?php } ?>
        <script type="text/javascript">
            function checkDel() {
                var con = confirm("ยืนยันการลบ ? ");
                if (con == true) {
                    return true;
                } else {
                    return false;
                }
            }
        </script>
        <table id="projectTable" width="100%" style="border: 1px solid black; border-collapse: collapse;" bordercolor="#333333" align="center" cellpadding="0" cellspacing="0">
            <tr> 
                <td  align="center" width="5%" bgcolor="#DFECFF" style="border: 1px solid black;">
                    <strong>ลำดับ
                        <br/>No.
                    </strong>
                </td>
                <td  align="center" width="30%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>รายการประเมิน
                        <br/>
                        Assessment
                    </strong>
                </td>
                <!--<td  align="center" width="30%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>ตัวบ่งชี้</strong></td>-->
                <td  align="center" width="17%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>วิธีการประเมินเเละเกณฑ์
                        <br/>Assessment methods and criteria</strong>
                </td>
                <td  align="center" width="8%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>คะเเนน
                        <br/>Score</strong>
                </td> 
                <td  align="center" width="10%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>เเก้ไข/ลบ
                        <br/>Edit/Delete</strong>
                </td> 
            </tr>
            <?php
            $sql_new_ability = "select * from evaulation.new_course_structure_ability where ";
            $sql_new_ability .= "evaulation.new_course_structure_ability.id_course ='" . $_POST['subject_id'] . "'";
            $sql_new_ability .= " and evaulation.new_course_structure_ability.delete ='0'";
            $sql_new_ability .= " order by evaulation.new_course_structure_ability.id_auto asc";
            $query_new_ablity = mysql_query($sql_new_ability) or die(mysql_error());
            $id_ab = 1;
            $sumProject = 0;
            while ($row_ability_data = mysql_fetch_array($query_new_ablity)) {
                $sumProject += $row_ability_data['score'];
                ?>
                <tr>
                    <td align='center' style="border: 1px solid black;"><?= $id_ab ?></td>
                    <td style="border: 1px solid black;padding: 0px 5px 0px 5px;"><?= $row_ability_data['ab'] ?></td>
                    <!--<td style="border: 1px solid black;padding: 0px 5px 0px 5px;"><?= $row_ability_data['indicator'] ?></td>-->
                    <td style="border: 1px solid black;padding: 0px 5px 0px 5px;"><?= $row_ability_data['evau'] ?></td>
                    <td style="border: 1px solid black;padding: 0px 5px 0px 5px;text-align: center;"><?= $row_ability_data['score'] ?></td>
                    <td style="border: 1px solid black;padding: 0px 5px 0px 5px;">
                        <br/>
                        <? if (empty($disble)) { ?>
                            <table align='center'>
                                <tr>
                                    <td>
                                        <form action="new_course_structure_edit_project.php" method="post" >
                                            <input type="hidden" name="id_edit" value='<?= $row_ability_data['id_auto'] ?>' />
                                            <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                            <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                            <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                            <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                            <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                            <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                            <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                            <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/>
                                            <input type="submit" value=' ' style="background: url('pic/edit.png');cursor: pointer;" title=" เเก้ไข "/>
                                        </form>
                                    </td>
                                    <td>
                                        <form action="new_course_structure_delete_project.php" method="post" onsubmit="return checkDel();">
                                            <input type="hidden" name="id_del" value='<?= $row_ability_data['id_auto'] ?>' />
                                            <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                            <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                            <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                            <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                            <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                            <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                            <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                            <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/>
                                            <input type="submit" value=" "  style="background: url('pic/delete.gif');cursor: pointer;" title=" ลบ "/>
                                        </form>
                                    </td>
                                </tr>
                            </table>
                        <? } ?>
                    </td>
                </tr>
                <?
                $id_ab++;
            }
            ?>
        </table>  
        <?
//        }
        if ($_POST['term'] == "ตลอดปี") {
            ?>
            <center>
                <table cellspacing="15">
                    <tr>
                        <td colspan="3" align="center"><strong>อัตราส่วนคะเเนน (Ratio)</strong></td>
                    </tr>
                    <tr>
                        <td>เก็บคะเเนนครั้งที่ 1 (Formative 1)</td>
                        <td><?= $formative1; ?></td>
                        <td>คะเเนน (Score)</td>
                    </tr>
                    <tr>
                        <td>สอบครั้งที่ 1 (Test 1)</td>
                        <td><?= $test1; ?></td>
                        <td>คะเเนน (Score)</td>
                    </tr>
                    <tr>
                        <td>เก็บคะเเนนครั้งที่ 2 (Formative 2)</td>
                        <td><?= $formative2; ?></td>
                        <td>คะเเนน (Score)</td>
                    </tr>
                    <tr>
                        <td>สอบครั้งที่ 2 (Test 2)</td>
                        <td><?= $test2; ?></td>
                        <td>คะเเนน (Score)</td>
                    </tr> 
                    <tr>
                        <td>เก็บคะเเนนครั้งที่ 3 (Formative 3)</td>
                        <td><?= $formative3; ?></td>
                        <td>คะเเนน (Score)</td>
                    </tr>
                    <tr>
                        <td>สอบครั้งที่ 3 (Test 3)</td>
                        <td><?= $test3; ?></td>
                        <td>คะเเนน (Score)</td>
                    </tr>
                    <tr>
                        <td>เก็บคะเเนนครั้งที่ 4 (Formative 4)</td>
                        <td><?= $formative4; ?></td>
                        <td>คะเเนน (Score)</td>
                    </tr>
                    <tr>
                        <td>สอบครั้งที่ 4 (Test 4)</td>
                        <td><?= $test4; ?></td>
                        <td>คะเเนน (Score)</td>
                    </tr>
                    <?
                    if ($pL == 0) {
                        ?>
                        <tr>
                            <td>Project/จิตพิสัย</td>
                            <td><?= $sumProject; ?></td>
                            <td>คะเเนน (Score)</td>
                        </tr>
                        <?
                    }
                    ?>
                    <tr>
                        <td align = "right"><strong>รวม</strong></td>
                        <td><?= $sumPoinAll = $test1 + $test2 + $test3 + $test4 + $formative1 + $formative2 + $formative3 + $formative4 + $sumProject
                    ?></td>
                        <td>คะเเนน (Score)</td>
                    </tr>

                </table>
                <br/>
                <span>
                    <?
                    if ($sumPoinAll <> 100) {
                        echo "<font color='red'>กรุณาตรวจสอบคะเเนนด้วยค่ะ (Check score.)</font>";
                    }
                    ?>
                </span>
            </center>
            <?
        } else {
            ?>
            <center>
                <table cellspacing="15">
                    <tr>
                        <td colspan="3" align="center"><strong>อัตราส่วนคะเเนน (Ratio)</strong></td>
                    </tr>
                    <tr>
                        <td>ก่อนกลางภาค (Formative 1)</td>
                        <td><?= $score1; ?></td>
                        <td>คะเเนน (Score)</td>
                    </tr>
                    <tr>
                        <td>กลางภาค (Midterm)</td>
                        <td><?= $score2; ?></td>
                        <td>คะเเนน (Score)</td>
                    </tr>
                    <tr>
                        <td>หลังกลางภาค (Formative 2)</td>
                        <td><?= $score3; ?></td>
                        <td>คะเเนน (Score)</td>
                    </tr>
                    <tr>
                        <td>ปลายภาค (Final)</td>
                        <td><?= $score4; ?></td>
                        <td>คะเเนน (Score)</td>
                    </tr>
                    <?
                    if ($pL == 0) {
                        ?>
                        <tr>
                            <td>Project/จิตพิสัย</td>
                            <td><?= $sumProject; ?></td>
                            <td>คะเเนน (Score)</td>
                        </tr>
                        <?
                    }
                    ?>
                    <tr>
                        <td align="right"><strong>รวม (Sum)</strong></td>
                        <td><?= $sumPoinAll = $score1 + $score2 + $score3 + $score4 + $sumProject; ?></td>
                        <td>คะเเนน (Score)</td>
                    </tr> 
                </table>
                <br/>
                <span>
                    <?
                    if ($sumPoinAll <> 100) {
                        echo "<font color='red'>กรุณาตรวจสอบคะเเนนด้วยค่ะ (Check score.)</font>";
                    }
                    ?>
                </span>
            </center>
        <?php }
        ?>
        <br/>
        <br/>
        <center>
            <input type="button" <?= $disble ?> onclick="$('#frmBack').submit();" value=" ยืนยัน /Confirm " <?
            if ($sumPoinAll <> 100) {
                echo "disabled";
            }
            ?>/>
            <input type="button" onclick="$('#frmBack').submit();" value=" ย้อนกลับ /Back " style="cursor: pointer;"/>
            &nbsp;

            <form action="new_course_display_detail.php" method="POST" style="padding-top: 15px;" id="frmBack">
                <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/>  
            </form> 
<!--            <input type="button" onclick="wi
                    ndow.location.replace('course_display_detail.php?subject=<?= $_POST['subject']; ?>&&subject_name=<?= $_POST['subject_name']; ?>&&unit=<?= $_POST['unit']; ?>&&term=<?= $_POST['term']; ?>&&year=<?= $_POST['year']; ?>&&classRoom=<?= $_POST['classRoom']; ?>');" value=" ย้อนกลับ "  />-->
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
mysql_free_result($rsnews);
mysql_free_result(rscat);
?>