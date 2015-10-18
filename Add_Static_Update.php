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
        <br>
        <h5 align="center"><font color="red">ทำการบันทึกเรียบร้อย</font></h5>
        <form align="center" action="new_test.php" method="POST" style="padding-top: 15px;">
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

<?
 require_once('Connections/bmks.php');
 require_once('Connections/bmksl.php');
	
 	$subject = $_GET['subject'];
 	$year = $_GET['year'];
 	$a1 = $_POST['a1'];
 	$a2 = $_POST['a2'];
 	$a3 = $_POST['a3'];
 	$Mean = $_POST['Mean'];
 	$Median =$_POST['Median'];
 	$SD = $_POST['SD'];
	$Variance = $_POST['Variance'];
	$type = $_GET['type'];
	mysql_query("Set names 'utf8'");
    $sql = "INSERT INTO log_test (id_course,year,Mean,Median,SD,Variance,a1,a2,a3) 
      VALUES ('$subject','$year','$Mean','$Median','$SD','$Variance','$a1','$a2','$a3')";
    mysql_query($sql);
    //echo $sql;

//////////////////////////////////////////////////////////////////////////////////////////////////
    if($a1 != ""){
    $tags = explode(',',$a1);

foreach($tags as $key) {    
   // echo $key."<br/>"; 
      	 $sql = "SELECT * FROM test WHERE id_course	 = '$subject' And num = $key AND type = '$type' ";
//echo $sql;
    $objQuery = mysql_query($sql);
    while($objResult = mysql_fetch_array($objQuery))
{
	$Subject_ID1 = $objResult["id_course"];
	$year1 = $objResult['year'];
	$num1 =  $objResult["num"];
	$text1 =  $objResult["text1"];
	$c11 = $objResult["c1"];
	$c21 =  $objResult["c2"];
	$c31 =  $objResult["c3"];
	$c41 =  $objResult["c4"];
	$ans1 =  $objResult["ans"];
	$obj1 =  $objResult["obj"];
	$Easy = "E";
		$sql2 = "INSERT INTO db_test (id_course,year,type,num,text1,c1,c2,c3,c4,ans,obj,Discrimination) 
      VALUES ('$Subject_ID1','$year1','$type','$num1','$text1','$c11','$c21','$c31','$c41','$ans1','$obj1','$Easy')";
    mysql_query($sql2);
}
 }
}

//////////////////////////////////////////////////////////////////////////////////////////////////
  if($a2 != ""){
   $tags2 = explode(',',$a2);

foreach($tags2 as $key2) {    
   // echo $key."<br/>"; 
      	 $sql = "SELECT * FROM test WHERE id_course	 = '$subject' And num = $key2  AND type = '$type'  ";
//echo $sql;
    $objQuery = mysql_query($sql);
    while($objResult = mysql_fetch_array($objQuery))
{
	$Subject_ID1 = $objResult["id_course"];
	$year1 = $objResult['year'];
	$num1 =  $objResult["num"];
	$text1 =  $objResult["text1"];
	$c11 = $objResult["c1"];
	$c21 =  $objResult["c2"];
	$c31 =  $objResult["c3"];
	$c41 =  $objResult["c4"];
	$ans1 =  $objResult["ans"];
	$obj1 =  $objResult["obj"];
	$Med = "M";
		$sql2 = "INSERT INTO db_test (id_course,year,type,num,text1,c1,c2,c3,c4,ans,obj,Discrimination) 
      VALUES ('$Subject_ID1','$year1','$type','$num1','$text1','$c11','$c21','$c31','$c41','$ans1','$obj1','$Med')";
    mysql_query($sql2);
}
 }
}
if ($a3 != "") {
	

     $tags3 = explode(',',$a3);

foreach($tags3 as $key3) {    
   // echo $key."<br/>"; 
      	 $sql = "SELECT * FROM test WHERE id_course	 = '$subject' And num = $key3  AND type = '$type'  ";
//echo $sql;
    $objQuery = mysql_query($sql);
    while($objResult = mysql_fetch_array($objQuery))
{
	$Subject_ID1 = $objResult["id_course"];
	$year1 = $objResult['year'];
	$num1 =  $objResult["num"];
	$text1 =  $objResult["text1"];
	$c11 = $objResult["c1"];
	$c21 =  $objResult["c2"];
	$c31 =  $objResult["c3"];
	$c41 =  $objResult["c4"];
	$ans1 =  $objResult["ans"];
	$obj1 =  $objResult["obj"];
	$Hard = "H";
		$sql2 = "INSERT INTO db_test (id_course,year,type,num,text1,c1,c2,c3,c4,ans,obj,Discrimination) 
      VALUES ('$Subject_ID1','$year1','$type','$num1','$text1','$c11','$c21','$c31','$c41','$ans1','$obj1','$Hard')";
    mysql_query($sql2);
}
 }
}

?>

<script type="text/javascript">
  //window.location.replace('new_test.php')
</script>
</body>
</html>