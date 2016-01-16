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
        <script type="text/javascript">
    function chk(){   
        var fty=new Array(".docx"); // ประเภทไฟล์ที่อนุญาตให้อัพโหลด   
        var a=document.form1.file.value; //กำหนดค่าของไฟล์ใหกับตัวแปร a   
        var permiss=0; // เงื่อนไขไฟล์อนุญาต
        a=a.toLowerCase();    
        if(a !=""){
            for(i=0;i<fty.length;i++){ // วน Loop ตรวจสอบไฟล์ที่อนุญาต   
                if(a.lastIndexOf(fty[i])>=0){  // เงื่อนไขไฟล์ที่อนุญาต   
                    permiss=1;
                    break;
                }else{
                    continue;
                }
            }  
            if(permiss==0){ 
                alert("อัพโหลดได้เฉพาะไฟล์ .docx");     
                return false;               
            }       
        }        
    }   
</script>
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
               <td><b>ชุด</b>&nbsp;<span class="eng">(Year)</span>: <?=$_POST['Id_Issue']; ?></td>
            </tr>
        </table>  
        <br/>
       
        <table width="100%" align="center">
            <tr align="center">
            
                <td >
                    <b>เลือกไฟล์ข้อสอบ</b>
                </td>
              
              
             
            </tr>
             <tr align="center">

                <td >
                     <form align="center"  action="upload_test_2.php?>"  method="post" enctype="multipart/form-data" onSubmit="JavaScript:return fncSubmit();">
                <br>
                
                <div align="center">
                <br><label><font color="red">*รูปแบบข้อสอบ----></font></label><a  href="filetemptest/temptest.docx">"คลิ๊ก"</a><BR>
                <input  type="file" name="file" />

                     <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                     <input type="hidden" name="Id_Issue" value="<?=$_POST['Id_Issue'];?>"/> 
                    <br><button type="submit"   name="btn-upload">บันทึก</button><BR>
                </div>
            </form>
                </td>
               
                
                
               
               
            </tr>
            
        </table>
<form align="center" action="new_course_display_detail_test" method="POST" style="padding-top: 15px;">
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


        <BR><BR>
        
      <div style="color:red;">
            <p><b><u>ขั้นตอนการเตรียมข้อสอบ Word ดังต่อไปนี้</u><b></p>
            <p>- ทำการตัดส่วนหัวข้อสอบ ให้มีเฉพาะตัวข้อสอบ ที่มีแต่ คำถาม และตัวลือก 4 ตัวเลือกเท่านั้น</p>
            <br><p><b><u>วิธีการพิมพ์ข้อสอบ</u><b></p>
            <p>- คำถาม : พิมพ์ เลขข้อตามด้วยเครื่องหมาย ) แล้วพิมพ์ จุด (.) แล้วตามด้วย เคาะวรรค อีก 1 ครั้งและพิมพ์คำถาม เช่น 1 . ข้อใดต่อไปนี้ถูกต้อง</p>
            <p>- ตัวเลือก : พิมพ์หมายเลขตัวเลือก (1,2,3,4) ตามด้วย จุด(.) เคาะวรรค 1 ครั้งและพิมพ์ตัวเลือก เช่น 3. 3+3 = 6</p>
            <p>- กรณีของโจทย์ข้อสอบมีรูปภาพ : พิมพ์ เลขข้อ เคาะวรรค 1 ครั้ง แล้วพิมพ์ จุด (.) แล้วตามด้วย เคาะวรรค อีก 1 ครั้งและทำการเพิ่มรูปภาพ</p>
            <p>- กรณีของตัวเลือกข้อสอบมีรูปภาพ : พิมพ์หมายเลขตัวเลือก (1,2,3,4) ตามด้วย จุด(.) เคาะวรรค 1 ครั้งและทำการเพิ่มรูปภาพ</p>
            <p>***ระบบยังไม่สามารถรองรับ รูปแบบสมการหรือรูปภาพที่วาดจากโปรแกรม Microsoft Word </p>
            <div border="1" style="margin-left:10%;float:left;color:blue;">
            <p><u>ตัวอย่างข้อสอบที่มีแต่ตัวอักษร</u></p>
            1). โรคใดไม่ถ่ายทอดทางพันธุกรรม<br>
                  &nbsp;&nbsp;1. เบาหวาน <br>
                  &nbsp;&nbsp;2. โรคเลือดใส <br>
                  &nbsp;&nbsp;3. โรคเหน็บชา <br>
                  &nbsp;&nbsp;4. โรคกล้ามเนื้อลีบ <br>
            </div>
             <div style="margin-left:10%;float:left;color:green;">
            <p><u>ตัวอย่างข้อสอบที่มีรูปภาพประกอบ</u></p>
            <p>1). ข้อใดคือสัญลักษณ์ประจำโรงเรียน</p>
                  &nbsp;&nbsp;1. <img width="60px" height="50px" src="/point/img/varee_logo.jpg"> <br>
                  &nbsp;&nbsp;2. รูปภาพ <br>
                  &nbsp;&nbsp;3. รูปภาพ <br>
                  &nbsp;&nbsp;4. รูปภาพ <br>
            </div>
</p>
    </div>

 
        </body>
