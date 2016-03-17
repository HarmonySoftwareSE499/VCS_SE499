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
$subone_name = "เลือกข้อสอบ (Choose Test)"; //หัวข้อหลัก
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

        function ch_box(id,newid,newsubject,newterm){
            //console.log(id);
           // console.log(newid);
            if(document.getElementById('ch_'+id).checked==true){
                document.getElementById("tab_data_"+id).style.backgroundColor = "#99FFCC";
               
                //send data in databse 
                $.ajax({
                    url:"file_insert_data.php",
                    type:"POST",
                    data:{type:"insert",IDtest:id,Id_New_Test:newid,subject:newsubject,term:newterm},
                    success:function(datareturn){
                        console.log(datareturn);
                         $('#use_point').html(datareturn);
                         //$('#show_cuse_'+id).html(datareturn);
                    }

                });
            }else{
                document.getElementById("tab_data_"+id).style.backgroundColor = "white";
                   $.ajax({
                    url:"file_delete_data.php",
                    type:"POST",
                    data:{type:"delete",IDtest:id,Id_New_Test:newid,subject:newsubject,term:newterm},
                    success:function(datareturn){
                        console.log(datareturn);
                       $('#use_point').html(datareturn);
                       //$('#show_cuse_'+id).html(datareturn);
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
        
         <h2>เลือกข้อสอบ <span class="eng">( Choose Test )</span></h2>

         <div class="panel-body">
         <?
$subject = $_GET['Subject'];
         ?>
     <form name="frmSearch" method="post" action="choose_test.php?Id_New_Test=<?=$_GET['Id_New_Test'];?>&Subject=<? echo $subject; ?>">
  <table width="599" border="0">
    <tr>
      <th>Select 
        <select name="ddlSelect" id="ddlSelect">
          <option>- Select -</option>
          <option value="yeardb" <?if($_POST["ddlSelect"]=="yeardb"){echo"selected";}?>>ปีการศึกษา</option>
          <option value="obj" <?if($_POST["ddlSelect"]=="obj"){echo"selected";}?>>ตัวชี้วัด</option>
          <option value="Discrimination" <?if($_POST["ddlSelect"]=="Discrimination"){echo"selected";}?>>คุณภาพข้อสอบ</option>
        </select>
        Keyword
                                    <input name="txtKeyword" type="text" id="txtKeyword" value="<?=$_POST["txtKeyword"];?>">
                                  
      <input type="submit" value="Search"></th>
    </tr>
  </table>
</form>
<div align="right">

<p data-toggle="modal" data-target="#myModal1"><a  target="blank" href="View_Test.php?Id_New_Test=<?=$_GET['Id_New_Test']?>"><h3>ดูตัวอย่างข้อสอบ</h3></a></p>
<p data-toggle="modal" data-target="#myModal1"><a  target="blank" href="View_Answer.php?Id_New_Test=<?=$_GET['Id_New_Test']?>"><h3>ดูตัวอย่างเฉลย</h3></a></p>
</div>
</div><!--View_Test.php?Id_New_Test=<?=$_GET['Id_New_Test']?>-->
</div>
 <table align="center" id="head_table" style=" background: #eeeeee;padding: 15px;"> 
                <tr>
                    <td><b>รหัสวิชา</b>
                        <span class="eng">(Code)</span>
                        &nbsp;:<?= $_GET['Subject']; ?></td>
                    <td>
                        <b>รายวิชา</b> <span class="eng">(Subject)</span> &nbsp;: <?= $_GET['subjectname']; ?>
                    </td>
                    <td>
                        <b>ภาคเรียนที่</b> <span class="eng">(term)</span> &nbsp;: <?= $_GET['term']; ?>
                    </td>
                </tr>
               
            </table>   
<?
mysql_query("Set names 'utf8'");


 $strSQL = "SELECT * FROM question INNER JOIN Examination ON question.IDtest=Examination.IDtest
            INNER JOIN issue_question ON issue_question.id_Issue=question.id_Issue
            WHERE issue_question.id_course  = '$subject' ";
    if($_POST["ddlSelect"] != "" and  $_POST["txtKeyword"]  != '')
    {
      $strSQL .= " AND (".$_POST["ddlSelect"]." LIKE '%".$_POST["txtKeyword"]."%' ) ";
    }   


    $objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");
    

$Qtotal = mysql_query($sql);
                      
$objQuery = mysql_query($strSQL) or die ("Error Query [".$strSQL."]");


$Num_Rows = mysql_num_rows($objQuery);
$Per_Page = 5;   // Per Page

$Page = $_GET["Page"];
if(!$_GET["Page"])
{
  $Page=1;
}

$Prev_Page = $Page-1;
$Next_Page = $Page+1;

$Page_Start = (($Per_Page*$Page)-$Per_Page);
if($Num_Rows<=$Per_Page)
{
  $Num_Pages =1;
}
else if(($Num_Rows % $Per_Page)==0)
{
  $Num_Pages =($Num_Rows/$Per_Page) ;
}
else
{
  $Num_Pages =($Num_Rows/$Per_Page)+1;
  $Num_Pages = (int)$Num_Pages;
}

$strSQL .=" order  by Examination.IDtest ASC LIMIT $Page_Start , $Per_Page";
$objQuery  = mysql_query($strSQL);

?>
<form align="center" action="create_new_test.php" method="POST" style="padding-top: 15px;">
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


<div>
<table width="100%" border="1">
   <tr>
        
        <th width="15%">ปีการศึกษา</th>
        <th>คำถาม&ตัวเลือก</th>
        <th>คุณภาพข้อสอบ</th>
        <th>จำนวนครั้งที่เคยเลือก</th>
        <th>เลือก</th>
    </tr>
    <?
 $sql = "SELECT * from new_test WHERE Subject = '".$_GET['Subject']."' AND term = '".$_GET['term']."'";
 $mysql1 = mysql_query($sql);
    $count_Ex_Result = mysql_fetch_array($mysql1);
   //$obj_re = mysql_fetch_array($objQuery);
//echo $obj_re['IDtest'];  
?>
    <h1 style="font-size:27px;" id="use_point" name="use_point" align="right">ข้อสอบที่สามารถเลือกได้ <?=$count_Ex_Result['point'];?> ข้อ </h1>

<?
$i=0;
while($objResult = mysql_fetch_array($objQuery))
  
{

  $chk_que1 =  explode("/9j/", $objResult["text1"]);
  //check 
  $sql_ch_test = "select * from evaulation.reference_test where ";
  $sql_ch_test .=" evaulation.reference_test.Id_New_Test ='".$_GET['Id_New_Test']."' ";
  $sql_ch_test .=" and evaulation.reference_test.IDtest ='".$objResult['IDtest']."'";
  $query_ch_test = mysql_query($sql_ch_test) or die(mysql_error());
  $ch_test = false;
  if(mysql_num_rows($query_ch_test)<>0){
    $ch_test = true;

  }

?>
    <tr  id='tab_data_<?=$objResult["IDtest"]?>'>
        
        <td align="center"><? echo $objResult['yeardb'];?></td>
        <td ><?
        $i++;
                            if (strlen($objResult["text1"]) > 5000 ) {
                            
                             ?><? echo $i.".".$chk_que1[0];?><br><img style="width:100px; float:none;" src="data:image/jpg;base64,/9j/<?=$chk_que1[1].$chk_que1[2]?>"><BR><?
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
                            echo "ตัวชี้วัด"."\t".$objResult["obj"];
                            ?></td>

        <td align="center"><? echo $objResult['Discrimination'];?></td>
        <td align="center">  
        <p id="show_cuse_<?=$objResult["IDtest"]?>" name="show_cuse"><?echo $objResult['C_use'];?></p>
        </td>
        <td align="center">
        <input 
        <?php
        if($ch_test){
          echo "checked='true'";

        }
        ?>
        onclick="ch_box('<?=$objResult["IDtest"]?>','<?=$_GET['Id_New_Test']?>','<?=$_GET['Subject'];?>','<?=$_GET['term'];?>')" id ='ch_<?=$objResult["IDtest"]?>' type="checkbox" name="MemberID[]" value="<?php echo $objResult["IDtest"];?>">เลือก<br>
        </td>
        </tr>
        <?
 if($ch_test){
  ?>
  <script type="text/javascript">
      document.getElementById("tab_data_"+<?=$objResult["IDtest"]?>).style.backgroundColor = "#99FFCC";
  </script>
  <?
 }
        ?>
        <?php
 }
 ?>
</table>

</h4></div>
<form align="center" action="create_new_test.php" method="POST" style="padding-top: 15px;">
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
</div>
<br>
Total <?php echo $Num_Rows;?> Record : <?php echo $Num_Pages;?> Page :
<?php
if($Prev_Page)
{
  echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$Prev_Page&Id_New_Test=$_GET[Id_New_Test]&Subject=$_GET[Subject]&subjectname=$_GET[subjectname]&term=ภาคเรียนที่%201'><< Back</a> ";
}

for($i=1; $i<=$Num_Pages; $i++){
  if($i != $Page)
  {
    echo "[ <a href='$_SERVER[SCRIPT_NAME]?Page=$i&Id_New_Test=$_GET[Id_New_Test]&Subject=$_GET[Subject]&subjectname=$_GET[subjectname]&term=ภาคเรียนที่%201'>$i</a> ]";
  }
  else
  {
    echo "<b> $i </b>";
  }
}
if($Page!=$Num_Pages)
{
  echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Next_Page&Id_New_Test=$_GET[Id_New_Test]&Subject=$_GET[Subject]&subjectname=$_GET[subjectname]&term=ภาคเรียนที่%201'>Next>></a> ";
}
mysql_close($objConnect);
?>
</body>
</html>
