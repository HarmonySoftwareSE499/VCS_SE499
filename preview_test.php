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
<script type="text/javascript">
        $('#myModal').on('shown.bs.modal', function () {
        $('#myInput').focus()
})

        function ch_box(id,newid,newsubject,newterm){
            //console.log(id);
           // console.log(newid);
       
            if(document.getElementById('ch_'+id).checked==true){
                document.getElementById("tab_data_"+id).style.backgroundColor = "#FFCC66";
               
                //send data in databse 
                $.ajax({
                    url:"file_insert_data_ex.php",
                    type:"POST",
                    data:{type:"insert",IDtest:id,Id_Issue:newid,subject:newsubject,term:newterm},
                    success:function(datareturn){
                        console.log(datareturn);
                         $('#use_point').html(datareturn);
                          var use_point = parseFloat(($('#use_point').html()).replace( /[^\d\.]*/g, ''));// เรียกจำนวนข้อสอบที่เลือก
                            console.log("---------"+use_point);
                           
                            var text_get_id = $('#idGetTestBuffer').val(); //text get id
                            console.log(text_get_id);
                            var substr_get_id =  text_get_id.split('####'); //ตัด string ด้วย #### 
                            if(use_point==0){ //ถ้า จำนวนที่เลือกเท่ากับ 0
                              for(var x = 0; x <= substr_get_id.length;x++){
                                if(document.getElementById('ch_'+substr_get_id[x])){ //ตรวจสอบหาว่า id นี้มีไหม
                                  if(document.getElementById('ch_'+substr_get_id[x]).checked!=true){  //ไม่มีการเลือกไว้  ให้ทำการ disbled
                                    document.getElementById('ch_'+substr_get_id[x]).disabled = true;  
                                  }
                                }
                              }
                            }else{
                              for(var x = 0; x <= substr_get_id.length;x++){
                                if(document.getElementById('ch_'+substr_get_id[x])){  //ตรวจสอบหาว่า id นี้มีไหม
                                  document.getElementById('ch_'+substr_get_id[x]).disabled =false;  //หากจำนวนที่เลือกได้ไม่เท่ากับ 0 ก็เปิดให้เลือกใหม่ได้ อีก
                                }
                              }
                            }
           
                    }

                });
            }else{
                document.getElementById("tab_data_"+id).style.backgroundColor = "white";
                   $.ajax({
                    url:"file_delete_data_ex.php",
                    type:"POST",
                    data:{type:"delete",IDtest:id,Id_Issue:newid,subject:newsubject,term:newterm},
                    success:function(datareturn){
                        console.log(datareturn);
                       $('#use_point').html(datareturn);
                        var use_point = parseFloat(($('#use_point').html()).replace( /[^\d\.]*/g, ''));// เรียกจำนวนข้อสอบที่เลือก
                      console.log("---------"+use_point);
                     
                      var text_get_id = $('#idGetTestBuffer').val(); //text get id
                      console.log(text_get_id);
                      var substr_get_id =  text_get_id.split('####'); //ตัด string ด้วย #### 
                      if(use_point==0){ //ถ้า จำนวนที่เลือกเท่ากับ 0
                        for(var x = 0; x <= substr_get_id.length;x++){
                          if(document.getElementById('ch_'+substr_get_id[x])){ //ตรวจสอบหาว่า id นี้มีไหม
                            if(document.getElementById('ch_'+substr_get_id[x]).checked!=true){  //ไม่มีการเลือกไว้  ให้ทำการ disbled
                              document.getElementById('ch_'+substr_get_id[x]).disabled = true;  
                            }
                          }
                        }
                      }else{
                        for(var x = 0; x <= substr_get_id.length;x++){
                          if(document.getElementById('ch_'+substr_get_id[x])){  //ตรวจสอบหาว่า id นี้มีไหม
                            document.getElementById('ch_'+substr_get_id[x]).disabled =false;  //หากจำนวนที่เลือกได้ไม่เท่ากับ 0 ก็เปิดให้เลือกใหม่ได้ อีก
                          }
                        }
                      }
                       
                    }

                });
            } 
           
        }
     
    
    //function call disbled ..........
    function disbleCheckbox(){
       
      var use_point = parseFloat(($('#use_point').html()).replace( /[^\d\.]*/g, ''));// เรียกจำนวนข้อสอบที่เลือก
      console.log("---------"+use_point);
     
      var text_get_id = $('#idGetTestBuffer').val(); //text get id
      console.log(text_get_id);
      var substr_get_id =  text_get_id.split('####'); //ตัด string ด้วย #### 
      if(use_point==0){ //ถ้า จำนวนที่เลือกเท่ากับ 0
        for(var x = 0; x <= substr_get_id.length;x++){
          if(document.getElementById('ch_'+substr_get_id[x])){ //ตรวจสอบหาว่า id นี้มีไหม
            if(document.getElementById('ch_'+substr_get_id[x]).checked!=true){  //ไม่มีการเลือกไว้  ให้ทำการ disbled
              document.getElementById('ch_'+substr_get_id[x]).disabled = true;  
            }
          }
        }
      }else{
        for(var x = 0; x <= substr_get_id.length;x++){
          if(document.getElementById('ch_'+substr_get_id[x])){  //ตรวจสอบหาว่า id นี้มีไหม
            document.getElementById('ch_'+substr_get_id[x]).disabled =false;  //หากจำนวนที่เลือกได้ไม่เท่ากับ 0 ก็เปิดให้เลือกใหม่ได้ อีก
          }
        }
      }
    }
    
    </script>

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
    <body onload='disbleCheckbox();'>
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
mysql_query("Set names 'utf8'");

 $Id_Issue = $_POST['Id_Issue'];
  $subject = $_POST['subject'];
$strSQL = "SELECT * FROM question WHERE Id_Issue = '$Id_Issue'  ";
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

$strSQL .=" order  by IDtest ASC LIMIT $Page_Start , $Per_Page";
$objQuery  = mysql_query($strSQL);

?>
<?
$sql = "SELECT * from new_test WHERE Subject = '".$_POST['subject']."' AND term = '".$_POST['term']."'";
 $mysql1 = mysql_query($sql);
    $count_Ex_Result = mysql_fetch_array($mysql1);
   //$obj_re = mysql_fetch_array($objQuery);
//echo $obj_re['IDtest'];  
?>



<div >
<form align="center" action="new_course_display_detail_test.php" method="POST" style="padding-top: 15px;">
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
<table width="100%" style="font-size:14px;" border="1"  >
<BR>
   <tr>
        
        <th>ชนิดข้อสอบ</th>
        <th>ข้อ</th>
        <th>คำถาม&ตัวเลือก</th>
        <th>คำตอบ</th>
        <th>ตัวชี้วัด</th>
        <th>เลือก</th>
    </tr>
<h1 style="font-size:27px;" id="use_point" name="use_point" align="right">ข้อสอบที่สามารถเลือกได้ <?=$count_Ex_Result['use_point'];?> ข้อ </h1>
 
<?

$subject = $_POST['subject'];
$sql_id_new = "SELECT * FROM new_test where Subject = '".$_POST['subject']."'";
$sql_id_new_re = mysql_query($sql_id_new);
$select_idnew = mysql_fetch_array($sql_id_new_re);
$select_idnew['Id_New_Test'];
$i = 1;
$text_get_ID = ''; //เก็บ Id ข้อ ไว้
while($objResult = mysql_fetch_array($objQuery))
{
     $chk_que1 =  explode("/9j/", $objResult["text1"]);


      //check 
  $sql_ch_test = "select * from evaulation.reference_test where ";
  $sql_ch_test .=" evaulation.reference_test.Id_New_Test = '".$select_idnew['Id_New_Test']."'";
  $sql_ch_test .=" and evaulation.reference_test.IDtest ='".$objResult['IDtest']."'";
  $query_ch_test = mysql_query($sql_ch_test) or die(mysql_error());
  $ch_test = false;
  if(mysql_num_rows($query_ch_test)<>0){
    $ch_test = true;

  }
?>
<?  

  //echo  $chk_que1 =  explode("/9j/", $objResult["text1"]);
    //echo print_r($chk_que1); 
    // $chk_que1[0];
    //echo $chk_que1[1];
    //echo $chk_que1[2];
//echo $objResult["IDtest"];
$text_get_ID .=$objResult["IDtest"]."####";  //ต่อ string  id test ไว้
?>
    <tr  id='tab_data_<?=$objResult["IDtest"]?>'>
      
        <td width="10%" align="center"><?= $_POST['term']; ?></td>
        <td width="10%" align="center"><?= $objResult["num"] ?></td>
        <td ><?
        
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
                            $i++;
                            ?></td>
 
        <td align="center"><? echo $objResult["ans"];?></td>
        <td align="center"><? echo $objResult["obj"];?></td>
        <td align="center">

         <input 
        <?php
        if($ch_test){
          echo "checked='true'";
       
        }
        ?>
        onclick="ch_box('<?=$objResult["IDtest"]?>','<?=$_GET['Id_New_Test']?>','<?=$_POST['subject'];?>','<?=$_POST['term'];?>')" id ='ch_<?=$objResult["IDtest"]?>' type="checkbox" name="MemberID[]" value="<?php echo $objResult["IDtest"];?>">เลือก<br>
        

        </td>
        </tr>
        <?
 if($ch_test){
  ?>
  <script type="text/javascript">
      document.getElementById("tab_data_"+<?=$objResult["IDtest"]?>).style.backgroundColor = "#FFCC66";
  </script>
  <?
 }
        ?>
        <?php
 }
 ?>
</table>
<input type='hidden' id='idGetTestBuffer' value='<?=$text_get_ID?>'/> <!-- เก็บค่า ID text เป็น Stirng  -->

 
 
<form align="center" action="new_course_display_detail_test.php" method="POST" style="padding-top: 15px;">
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
  echo " <a href='$_SERVER[SCRIPT_NAME]?Page=$Prev_Page'><< Back</a> ";
}

for($i=1; $i<=$Num_Pages; $i++){
  if($i != $Page)
  {
    echo "[ <a href='$_SERVER[SCRIPT_NAME]?Page=$i'>$i</a> ]";
  }
  else
  {
    echo "<b> $i </b>";
  }
}
if($Page!=$Num_Pages)
{
  echo " <a href ='$_SERVER[SCRIPT_NAME]?Page=$Next_Page'>Next>></a> ";
}
mysql_close($objConnect);
?>
</body>
</html>
