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
        .exa-detail-full {
        /* width: 100%; */
        min-height: 200px;
        margin: 0 auto;
        /* margin-top: 10px; */
        padding: 15px 10px 10px;
        font-family: tahoma;
        display: inherit;
        background-color: #f3f4f4;
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
         
              <form action="Create_Question_Add.php"   method="POST">
                    <table>
                        <tr>
                           
                            <td>
                                  <select onclick="post" style="width: 300px;" name="type">
                                    <option value="">-- เลือกชนิดการสอบ ( Type Examination ) --</option>
                                    <option value="GNRL">ข้อสอบทั่วไป</option> 
                                    <option value="MID1">ข้อสอบสอบกลางภาค 1</option> 
                                    <option value="FIN1">ข้อสอบสอบปลายภาค 1</option> 
                                    <option value="MID2">ข้อสอบสอบกลางภาค 2</option> 
                                    <option value="FIN2">ข้อสอบสอบปลายภาค 2</option>
                                    <option value="SUM">ข้อสอบสอบซัมเมอร์</option> 
                                    <option value="EQU">ข้อสอบสอบเทียบโอน</option>
                                    <option value="ADM">ข้อสอบสอบเข้า</option> 
                                </select>
                                    <input type="hidden" name="subject_id" value="<?= $_POST['subject_id']; ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/>     
                            <td><input  type="submit" name="create"  value=" สร้าง "/></td>
                        </tr>

                    </table>
                </form>

<?
$subject0 = $_POST['subject'];
    $sql1 = "SELECT * from Issue_question  where id_course = '$subject0'";
    //echo $sql1;
    $objquery = mysql_query($sql1);
    ?>
    <table border="1">
    <th>นำเข้าข้อสอบครั้งที่</th>
    <th>รหัสวิชา</th>
    <th>ชื่อวิชา</th>
    <th>ชนิดข้อสอบ</th>
    <th>จำนวนข้อ</th>
    <th>เวลาที่สร้าง</th>
    <th>ผู้สร้าง</th>
    <th>จัดการข้อสอบ</th>
    <th>แก้ไข</th>
    <th>ลบ</th>
    <?
    $i = 1;
    while ($objResult = mysql_fetch_array($objquery)) {?>
    
                                <tr>
                                    <td align="center" width="10%" height="25" style="border: 1px solid black;"><? echo $i ;?></td>
                                    <td align="center" width="10%" style="border: 1px solid black;padding-left: 5px;padding-right: 5px;" class="paddingLeftTable"><?=$objResult['id_course'];?></td>
                                    <td width="20%" style="border: 1px solid black;" align="center">
                                     <form align="Left" action="preview_test.php" method="POST">
                                <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    <input type="hidden" name="type" value="<?= $_POST['type']; ?>"/>
                                    <input type="hidden" name="Id_Issue" value="<?=$objResult['Id_Issue'];?>"/> 
                                 <input  type="submit" value="<? echo $objResult['name_course'] ;?>" style="width:100%; border: 0;background: none;color: #2371E2;cursor: pointer;"/>
                                </form>
                                    
                                    </td>
                                    <td width="10%" style="border: 1px solid black;" align="center"><?=$objResult['type'];?></td>
                                    <? 
                                    $Id_Issue_SQL = $objResult['Id_Issue'];
                                    $result_count = mysql_query("SELECT * FROM question Where Id_Issue = '$Id_Issue_SQL' ");
                                    $result_count1 = mysql_num_rows($result_count);
                                    //echo $result_count1;
                                    ?>
                                    <td width="10%" style="border: 1px solid black;" align="center"><?=$result_count1?></td>
                                    <td width="10%" style="border: 1px solid black;" align="center"><?=$objResult['Time'];?></td>
                                    <td width="10%" align="center" style="border: 1px solid black;"><?=$objResult['creater'];?></td>
                                    <td align="center" width="10%" style="border: 1px solid black;">
                                     <form  align="Left" action="new_test.php" method="POST">
                                    <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    <input type="hidden" name="Id_Issue" value="<?=$objResult['Id_Issue'];?>"/> 
                                    <!--<input type="image" src="pic/add.gif" alt="Submit" style="border: none;background: none;color: #2371E2;"/>-->
                                    <input type="submit" value="นำเข้าข้อสอบ" style="width:100%; border: 2;background: none;color: #2371E2;cursor: pointer;"/> 
                                </form>
                                <form  align="Left" action="new_ans.php" method="POST">
                                    <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    <input type="hidden" name="type" value="<?= $_POST['type']; ?>"/>
                                    <input type="hidden" name="Id_Issue" value="<?=$objResult['Id_Issue'];?>"/> 
                                    <input type="submit" value="นำเข้าเฉลย" style=" width:100%;border: 2;background: none;color: #2371E2;cursor: pointer;"/>
                                </form>
                                 <form align="Left" action="test_object.php" method="POST">
                                    <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    <input type="hidden" name="type" value="<?= $_POST['type']; ?>"/>
                                    <input type="hidden" name="Id_Issue" value="<?=$objResult['Id_Issue'];?>"/> 
                                    <input type="submit" value="กำหนดตัวชี้วัด" style="width:100%; border: 2;background: none;color: #2371E2;cursor: pointer;"/>
                                </form>
                                <form align="Left" action="test_static.php" method="POST">
                                    <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    <input type="hidden" name="type" value="<?= $_POST['type']; ?>"/>
                                    <input type="hidden" name="Id_Issue" value="<?=$objResult['Id_Issue'];?>"/> 
                                    <input type="submit" value="เพิ่มข้อสอบที่ควรเก็บ" style="width:100%; border: 2;background: none;color: #2371E2;cursor: pointer;"/>
                                </form>  
                                </td>
                                <td width="5%" align="center" style="border: 1px solid black;">
                                     <form align="Left" action="Edit_Delete_AllTest.php" method="POST">
                                <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    <input type="hidden" name="type" value="<?= $_POST['type']; ?>"/>
                                    <input type="hidden" name="Id_Issue" value="<?=$objResult['Id_Issue'];?>"/> 
                                 <input  type="submit" value="แก้ไข" style="width:100%; border: 2;background: none;color: #2371E2;cursor: pointer;"/>
                                </form>
                                </td>
                                <td width="10%" align="center" style="border: 1px solid black;">
                                <form align="Left" action="DeleteNewissue.php?Id_Issue=<?php echo $objResult["Id_Issue"];?>" method="POST">
                                <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    <input type="hidden" name="type" value="<?= $_POST['type']; ?>"/>
                                    <input type="hidden" name="Id_Issue" value="<?=$objResult['Id_Issue'];?>"/> 
                                 <input onclick="return confirm('คุณต้องการลบข้อมูลที่เลือก')" type="submit" value="ลบ" style="width:100%; border: 2;background: none;color: #2371E2;cursor: pointer;"/>
                                </form>
                                </td>
                                    
                                </tr>
                      <?
    $i++;
    }
    ?> 
                            
      
                </table> 
            

            <? 
            $id_course_row = $_POST['subject'];
            //echo $id_course_row;
            //$sql = "SELECT * FROM Examination where id_course = '$id_course_row' ";
            //$sql1 = "SELECT * FROM question where id_course = '$id_course_row' ";
            //echo $sql;
            $result = mysql_query($sql);
            $result1 = mysql_query($sql1);
          //  $num_rows1 = mysql_num_rows($result1);
            //$num_rows = mysql_num_rows($result);
             ?>
          <!--  <p style="color:red;" align="center">จำนวนข้อสอบทั้งหมดในคลังข้อสอบส่วนกลาง มีจำนวน <? echo $num_rows;?> ข้อ</p>
            <p style="color:red;" align="center">จำนวนข้อสอบทั้งหมดในคลังข้อสอบวิชา <u><? echo $_POST['subject'];?></u> มีจำนวน <? echo $num_rows1;?> ข้อ</p>
            --><?

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
                    <!--
                    <table cellspacing="15">
                      <tr>
                            <td><img src="pic/OK.gif"></img></td>
                            <td><a href="attribute_of_school_course.php?subject=<?= $_POST['subject']; ?>&&subject_name=<?= $_POST['subject_name']; ?>&&unit=<?= $_POST['unit']; ?>&&term=<?= $_POST['term']; ?>&&year=<?= $_POST['year']; ?>&&classRoom=<?= $_POST['classRoom']; ?>">คุณลักษณะอันพึงประสงค์ของโรงเรียน <span class="eng">( School desirable characteristics )</span></a></td>
                        </tr>
                         
                        
                            
                            <td align="left">
                                <form action="preview_test_all.php" method="POST" style="padding-top: 15px;">
                                    <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    <input type="submit" value="ดูข้อสอบทั้งหมด  ( View Test All )  " style="border: none;background: none;color: #2371E2;cursor: pointer;"/>
                                </form> 
                                </td>
                              
                                <a href="new_course_structure.php?subject=<?= $_POST['subject']; ?>&&subject_name=<?= $_POST['subject_name']; ?>&&unit=<?= $_POST['unit']; ?>&&term=<?= $_POST['term']; ?>&&year=<?= $_POST['year']; ?>&&classRoom=<?= $_POST['classRoom']; ?>">จัดการโครงสร้างเเนื้อหา <span class="eng"> ( Management of content structure ) </span></a></td>
                        </tr>
                        <tr>
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
                                    <input type="submit" value="การนำข้อสอบเข้าคลัง  ( Import Test To Bank )  " style="border: none;background: none;color: #2371E2;cursor: pointer;"/>
                                </form> 
                                </td>
                        </tr>
                           <tr>
                            <td align="left">
                                <form action="Edit_Delete_AllTest.php" method="POST" style="padding-top: 15px;">
                                    <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    <input type="submit" value="แก้ไข/ลบ ข้อสอบ  ( Edit/Delete Test )  " style="border: none;background: none;color: #2371E2;cursor: pointer;"/>
                                </form> 
                                </td>
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
                             
                        <? } ?>
                        <input type="button" value=" Back " onclick="$('#backUrl').submit();"/>

                        <form action="new_course_display_test.php" method="POST" id="backUrl"> 
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