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

$terms = $_POST['terms'];
$years = $_POST['years'];
//echo $terms;
 $strSQL ="SELECT * from subject where myear = '$years' and term = '$terms' ";
 $objQuery  = mysql_query($strSQL);


?>
<select id='subject' name="subject">
<?
while ($objResult = mysql_fetch_array($objQuery)) {
	?>
	<option value="<?=$objResult['SCODE'].$objResult['SNAME']?>"><?=$objResult['SCODE']."-".$objResult['SNAME']?></option>
	<?
}
?>
	
</select>