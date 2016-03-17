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
        <script type="text/javascript">
        function findsubject(){
          var year = $('#year').val();
          var term = $('#term').val();
          //console.log(year+term);
          $.ajax({
            type:"POST",
            url:"findsubject.php", 
            data:{years:year,terms:term},
            success:function(data){
               console.log(data);
               $('#subject').html(data);
            }
          });

        }
   

        </script>
        <!-- สิ้นสุดสคริป -->
    </head>
    <body>
        <?php include '../mainsystem/inc_head.php'; ?>
        <?php include '../mainsystem/inc_menu_point.php'; ?>
        <?php include '../mainsystem/inc_befordata.php'; ?>
        <!-- เริ่มข้อความ -->
        <center>
         <h2>โครงสร้างเข้อสอบ <span class="eng">( Test Structure )</span></h2>
          <?
            if (empty($msubject)) {
                echo "<br/><br/><br/><br/><br/><br/><b><font color='red'>ระบบตรวจสอบว่าท่านยังไม่มีกลุ่มสาระ กรุณาติดต่อหัวหน้ากลุ่มสาระของท่าน</font></b><br/><br/><br/><br/><br/><br/><br/>";
            } else {
                ?>
                <form action="Create_Test_Add.php"   method="POST">
                    <table>
                        <tr>
                            <td>ปีการศึกษา <span class="eng">( Acadamic year )</span></td>
                            <td>
                                <select id="year" style="width: 100%" name="year">
                                    <option value="" >-- เลือกปีการศึกษา ( Year ) --</option>
                                    <?
                                    $strSQL ="SELECT DISTINCT myear from subject where myear >= '2558'";

                                    $objQuery  = mysql_query($strSQL);
                                    ?>  
                        <?php 
                        while($objResult = mysql_fetch_array($objQuery)){?>
                        <option value="<? echo $objResult["myear"];?>" ><? echo $objResult["myear"];?></option>
                       <?
                        }
                        ?> 
                                </select>
                            </td>
                        </tr>
                         <tr>
                            <td>ชนิดการสอบ <span class="eng">( Type Test )</span></td>
                            <td>
                                <select style="width: 100%;" name="type">
                                    <option value="">-- เลือกชนิดการสอบ ( Type Examination ) --</option>
                                    <option value="กลางภาค 1">ข้อสอบสอบกลางภาค 1</option> 
                                    <option value="ปลายภาค 1">ข้อสอบสอบปลายภาค 1</option> 
                                    <option value="กลางภาค 2">ข้อสอบสอบกลางภาค 2</option> 
                                    <option value="ปลายภาค 2">ข้อสอบสอบปลายภาค 2</option>
                                    <option value="ซัมเมอร์">ข้อสอบสอบซัมเมอร์</option> 
                                    <option value="เทียบโอน">ข้อสอบสอบเทียบโอน</option>
                                    <option value="สอบเข้า">ข้อสอบสอบเข้า</option> 
                                </select>
                            </td>
                        </tr> 
                        <tr>   
                            <td>ภาคเรียน <span class="eng">( Semester )</span></td>
                            <td>
                                <select onchange="findsubject()" id="term" style="width: 100%;" name="term"> 


                                    <option value="">-- เลือกภาคเรียน ( Semester )--</option>
                                    <option value="ภาคเรียนที่ 1">ภาคเรียนที่ 1 ( Semester 1 )</option>
                                    <option value="ภาคเรียนที่ 2">ภาคเรียนที่ 2 ( Semester 2 )</option>
                                </select>
                            </td>
                        </tr>

                         <tr>
                            <td>ชื่อวิชา <span class="eng">( Subject )</span></td>
                            <td>
                                <select id="subject" style="width: 100%;" name="subject">
                                    <option value="" >-- เลือกวิชา ( Subject ) --</option>
                                </select>
                            </td>
                           </tr>
                        <tr>
                            <td>ระดับชั้น <span class="eng">( Level )</span></td>
                            <td>
                                <select id="level" style="width: 100%;" name="level">
                                    <option value="" >-- เลือกระดับชั้น ( Level ) --</option>
                                    <?
                                    $strSQL1 ="SELECT DISTINCT CNAME from room ";

                                    $objQuery1  = mysql_query($strSQL1);
                                    ?>  
                        <?php 
                        while($objResult1 = mysql_fetch_array($objQuery1)){?>
                        <option value="<? echo $objResult1["CNAME"];?>" ><? echo $objResult1["CNAME"];?></option>
                       <?
                        }
                        ?> 
                                </select>
                            </td>
                        </tr>
                        <tr>
                        <tr>
                            <td>จำนวนข้อ <span class="eng">( Point )</span></td>
                            <td>
                                <input type="text" name="point" placeholder="กรุณากรอกจำนวนข้อ">  จำนวนข้อ
                            </td>
                        </tr>
                        <tr>
                            <td>จำนวนข้อสอบที่ครูสามารถออกได้ <span class="eng">( Teacher Request Point )</span></td>
                            <td>
                                <select style="width: 100%;" name="trpoint">
                                    <option value="">-- เลือกจำนวนเปอร์เซ็น ( percent ) --</option>
                                    <option value="10">10%</option> 
                                    <option value="20">20%</option> 
                                    <option value="30">30%</option> 
                                    <option value="40">40%</option>
                                    <option value="50">50%</option> 
                                    <option value="60">60%</option>
                                    <option value="70">70%</option> 
                                    <option value="80">80%</option> 
                                    <option value="90">90%</option>
                                    <option value="100">100%</option>  
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <td>คะแนน <span class="eng">( Score )</span></td>
                            <td>
                                <input type="text" name="score" placeholder="กรุณากรอกจำนวนคะแนน">  คะแนน
                            </td>
                        </tr>
                         <tr>
                            <td>เวลาสอบ <span class="eng">( Time )</span></td>
                            <td>
                                <input type="text" name="time" id="time" placeholder="กรุณากรอกเวลาการสอบ">  นาที
                            </td>
                        </tr>
                        <tr>


                            <td></td>
                            <td><input onclick="create_test()" type="submit" name="create"  value=" สร้าง "/></td>
                        </tr>

                    </table>
                </form>

                <br/>


                <table width="100%" style="border: 1px solid black; border-collapse: collapse;" bordercolor="#333333" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" width="10%" bgcolor="#DFECFF" height="35" style="border: 1px solid black;"><strong>รหัสข้อสอบ</strong>
                            <br/>
                            <span class="eng">Code</span>
                        </td>
                        <td align="center" width="10%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>รหัสวิชา</strong>
                            <br/>
                            <span class="eng">Subject</span>
                        </td>
                        <td align="center" width="10%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>ชื่อวิชา</strong>
                            <br/>
                            <span class="eng">Subject Name</span>
                        </td>
                        <td align="center" width="10%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>ชนิดการสอบ</strong>
                            <span class="eng">Type</span>
                        </td>
                        <td align="center" width="10%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>ปีการศึกษา</strong>
                            <span class="eng">Year</span>
                        </td>
                        <td align="center" width="10%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>ภาคเรียน</strong>
                            <br/>
                            <span class="eng">Semester</span>
                        </td>
                        <td align="center" width="10%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>ผู้สร้างข้อสอบ</strong>
                            <br/>
                            <span class="eng">Creator</span>
                        </td>
                        <td align="center" width="15%" bgcolor="#DFECFF" style="border: 1px solid black;"><strong>แก้ไข้อสอบ/ลบ</strong>
                            <br/>
                            <span class="eng">Edit/Delete</span>
                        </td>
                    </tr>
    <?
    $sql1 = "SELECT * from new_test";
    $objquery = mysql_query($sql1);
    while ($objResult = mysql_fetch_array($objquery)) {?>
    
                                <tr>
                                    <td align="center" width="10%" height="25" style="border: 1px solid black;"><?=$objResult['Id_New_Test'];?></td>
                                    <td align="center" width="10%" style="border: 1px solid black;padding-left: 5px;padding-right: 5px;" class="paddingLeftTable"><?=$objResult['Subject'];?></td>
                                    
                                    <td width="25%" style="border: 1px solid black;" align="center">

                                    <form align="Left" action="choose_test.php?Id_New_Test=<?=$objResult['Id_New_Test'];?>&Subject=<?=$objResult['Subject'];?>&subjectname=<?=$objResult['subjectname'];?>&term=<?=$objResult['term'];?>" method="POST">
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
                                 <input  type="submit" value="<? echo $objResult['subjectname'] ;?>" style="width:100%; border: 0;background: none;color: #2371E2;cursor: pointer;"/>
                                </form>
                                    </td>
                                    <td width="10%" style="border: 1px solid black;" align="center"><?=$objResult['type'];?></td>
                                    <td width="10%" style="border: 1px solid black;" align="center"><?=$objResult['year'];?></td>
                                    <td width="10%" style="border: 1px solid black;" align="center"><?=$objResult['term'];?></td>
                                    <td width="10%" align="center" style="border: 1px solid black;"><?=$objResult['creater'];?>
                                    </td>
                                    <td align="center" width="15%" style="border: 1px solid black;">
                                    <a href="Edit_Head_Test.php?Id_New_Test=<?=$objResult['Id_New_Test'];?>"><img src="pic/edit.png" title="แก้ไขข้อสอบ "></a>
                                    <a onclick="return confirm('คุณต้องการลบข้อมูลที่เลือก')" href="DeleteNewTest.php?Id_New_Test=<?php echo $objResult["Id_New_Test"];?>';}"><img src="pic/delete.gif" ></a></td>
                                </tr>
                      <?
    }
    ?> 
                            
      
                </table> 
            <? } ?> 
