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
//header("Content-Type: $mime;charset=$charset");

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
$subone_name = "ดูข้อสอบทั้งหมด (View All Test)"; //หัวข้อหลัก
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
        $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').focus()
})

        function ch_box(id,newid){
            //console.log(id);
           // console.log(newid);
            if(document.getElementById('ch_'+id).checked==true){
                document.getElementById("tab_data_"+id).style.backgroundColor = "#99FFCC";
                //send data in databse 
                $.ajax({
                    url:"file_insert_data.php",
                    type:"POST",
                    data:{type:"insert",IDtest:id,Id_New_Test:newid},
                    success:function(datareturn){
                        console.log(datareturn);
                    }

                });
            }else{
                document.getElementById("tab_data_"+id).style.backgroundColor = "white";
                   $.ajax({
                    url:"file_delete_data.php",
                    type:"POST",
                    data:{type:"delete",IDtest:id,Id_New_Test:newid},
                    success:function(datareturn){
                        console.log(datareturn);
                    }

                });
            }
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
        
         <h2>ค้นหาข้อสอบ <span class="eng">( Search Test )</span></h2>
         <div class="panel-body">
     <form name="frmSearch" method="post" action="choose_test.php?Id_New_Test=<?=$_GET['Id_New_Test']?>">
  <table width="599" border="0">
    <tr>
      <th>Select 
        <select name="ddlSelect" id="ddlSelect">
          <option>- Select -</option>
          <option value="year" <?if($_POST["ddlSelect"]=="year"){echo"selected";}?>>ปีการศึกษา</option>
          <option value="obj" <?if($_POST["ddlSelect"]=="obj"){echo"selected";}?>>วัตถุประสงค์</option>
          <option value="Discrimination" <?if($_POST["ddlSelect"]=="Discrimination"){echo"selected";}?>>อำนาจจำแนก</option>
        </select>
        Keyword
        <input name="txtKeyword" type="text" id="txtKeyword" value="<?=$_POST["txtKeyword"];?>">
      <input type="submit" value="Search"></th>
    </tr>
  </table>
</form>
<div align="right">

</div>
</div><!--View_Test.php?Id_New_Test=<?=$_GET['Id_New_Test']?>-->
</div>
<?
mysql_query("Set names 'utf8'");

$subject1 = $_POST['subject'];
$strSQL = "SELECT * FROM test where id_course = '$subject1'";
//echo $strSQL;

    if($_POST["ddlSelect"] != "" and  $_POST["txtKeyword"]  != '')
    {
      $strSQL .= " WHERE (".$_POST["ddlSelect"]." LIKE '%".$_POST["txtKeyword"]."%' ) ";
    }   


    $objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
    

$Qtotal = mysql_query($sql);
                      
$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");


$Num_Rows = mysql_num_rows($objQuery);



$strSQL .=" order  by IDtest DESC ";
$objQuery  = mysql_query($strSQL);
?>



<div>
<table width="100%" border="1">
   <tr>
        <th width="10%">รหัสวิชา</th>
        <th width="15%">ปีการศึกษา</th>
        <th>คำถาม&ตัวเลือก</th>
    </tr>
<?
$i=0;
while($objResult = mysql_fetch_array($objQuery))
  
{
?>
    <tr id='tab_data_<?=$objResult["IDtest"]?>'>
        <td align="center"><?php echo $objResult['id_course'] ;?></td>
        <td align="center"><? echo $objResult['year'];?></td>
        <td ><?
        $i++;
                            if (strlen($objResult["text1"]) > 5000 ) {
                             
                             ?><? echo $i;?> <img style="width:100px; float:none;" src="data:image/jpg;base64,<?=$objResult["text1"]?>"><BR><?
                           }else{
                            echo "$i".".".$objResult["text1"]."<BR>"; 
                                }
                            if (strlen($objResult["c1"]) > 5000 ) {
                             
                             ?>1. <img style="width:100px; float:none;" src="data:image/jpg;base64,<?=$objResult["c1"]?>"><BR><?
                           }else{
                            echo "1. ".$objResult["c1"]."<BR>"; 
                                }
                                  if (strlen($objResult["c2"]) > 5000 ) {
                             ?>2. <img style="width:100px; float:none;" src="data:image/jpg;base64,<?=$objResult["c2"]?>"><BR><?
                           }else{
                            echo "2. ".$objResult["c2"]."<BR>"; 
                                }
                                if (strlen($objResult["c3"]) > 5000 ) {
                           ?>3. <img style="width:100px; float:none;" src="data:image/jpg;base64,<?=$objResult["c3"]?>"><BR><?
                           }else{
                            echo "3. ".$objResult["c3"]."<BR>"; 
                        }if (strlen($objResult["c4"]) > 5000 ) {
                           ?>4. <img style="width:100px; float:none;" src="data:image/jpg;base64,<?=$objResult["c4"]?>"><BR><?
                           }else{
                            echo "4. ".$objResult["c4"]."<BR>";
                        }
                            echo "คำตอบที่ถูกต้อง"."\t".$objResult["ans"]."<BR>";
                            echo "วัตถุประสงค์"."\t".$objResult["obj"];
                            ?></td>

        <?php


        ?>
        </tr>
        <?php
 }
 ?>
</table>

</h4></div>
</div>