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
$subone_name = "การจัดการข้อสอบ ( Test management )"; //หัวข้อหลัก
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

<?
if (isset($_POST['btn-upload'])) {
	
	$Indicator = $_POST["lmName1"];
	$a1 = $_POST['a1'];
    //echo $Indicator;

	$type = $_POST['type'];
	//echo $type;
	mysql_query("Set names 'utf8'");
    //$sql = "UPDATE test SET obj = $Indicator WHERE num = $i";
    //mysql_query($sql);

        if($a1 != ""){
         $chk_1 = strpos($a1,'-');
           if ($chk_1 > 0) {
              $tags = explode("-", $a1);
              //print_r($tags);
             $countlast = count($tags)-1;
              $tags[$countlast];
              //echo $tags[0];
              //echo $tags[1];
              //echo $tags[2];
               $chkcount = count($tags);
if ($chkcount = 3) {
    # code...
}
if ($chkcount = 4) {
    $chkc1 = $tags[1];
    $chkc1 = explode(",", $chkc1);
    $block = $chkc1[1]-$chkc1[0]-1;
    $block = $block+$chkc1[0];

    $chkc2 = $tags[2];
    $chkc2 = explode(",", $chkc2);
    //print_r($chkc2);
    $block2 = $chkc2[1]-$chkc2[0]-1;
     $block2 = $block2+$chkc2[0];
   //echo $chkc1 = $tags[2];
}
              for ($i=$tags[0]; $i <= $tags[$countlast]; $i++) { 
                  //echo $i;
             if ($i != $block && $i != $block2) {
                    $sql1 = "SELECT question.obj
                    FROM question
                    INNER JOIN issue_question
                    ON question.Id_Issue=issue_question.Id_Issue where `num` = $i";
                    //$sql1 = "where `num` = $key";
                    //echo $sql1.'<BR>'; 
                    $objQuery = mysql_query($sql1);
       while($objResult = mysql_fetch_array($objQuery))
{
    $obj1 = $objResult['obj'];
}
if(empty($obj1)){
         $sql = "UPDATE  `evaulation`.`question` SET  `obj` =  '$Indicator' WHERE  `num` = $i ";
        }
        if(!empty($obj1)){
           $strobj = $obj1.",".$Indicator ;
           $a = implode(',',array_unique(explode(',', $strobj)));
           //echo $a;
         $sql = "UPDATE  `evaulation`.`question` SET  `obj` =  '$a'  WHERE `num` = $i ";
        }
//echo $sql."<BR>";
    mysql_query($sql);
             }
   // $tags = explode(',',$a1);

   
    //echo $key."<br/>"; 
       
       
   
 
}
 }
 }    
}
?>
<br>
<h5 align="center">ตัวชี้วัด ที่ <? echo $Indicator ?> ได้แก่ข้อ : <? echo $a1 ?></h5>
<form align="center" action="test_object.php" method="POST" style="padding-top: 15px;">
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
                                    <input type="submit" value="เพิ่มตัวชี้วัด  " style="border: 2;background: none;color: #2371E2;cursor: pointer;"/>
                                    
                                </form> 
                                
</body>
<script type="text/javascript">
  //window.location.replace('test_object.php')
</script>
</html>
