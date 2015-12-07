<?php @session_start();
?>
<?php require_once('Connections/bmks.php'); ?>
<?php require_once('Connections/bmksl.php'); ?>
<?require_once './simple_html_dom.php';?>
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
        <script type="text/javascript">
    function chk(){   
        var fty=new Array(".doc",".docx"); // ประเภทไฟล์ที่อนุญาตให้อัพโหลด   
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
                alert("อัพโหลดได้เฉพาะไฟล์ .doc , .docx");     
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
                <td><b>ขนิดการสอบ</b>&nbsp;<span class="eng">(Type Exam)</span>: <?= $_POST['type']; ?></td>

                <td></td>
            </tr>
        </table>  
        <br/>
<?php
function showimage($file_name_image) {
    $pic = 'docx/word/' . $file_name_image;

    $picture = base64_encode(file_get_contents($pic));
   // echo '<img src="' . $pic . '">';
   // exit();
//    
////    echo $zip_file_original = $zip_file_original;
////    $file_name_image = 'docx/word/' . $file_name_image . ''; // getting the image in the zip using its name
////    //echo "<br/>";
////    $z_show = new ZipArchive();
////    if ($z_show->open($zip_file_original) !== true) {
////        echo "File not found.";
////        return false;
////    }
////
////    $stat = $z_show->statName($file_name_image);
////    $fp = $z_show->getStream($file_name_image);
////    if (!$fp) {
////        echo "Could not load image.";
////        return false;
////    }
//
//    header('Content-Type: image/jpeg');
//    header('Content-Length: ' . $stat['size']);
//    $image = stream_get_contents($fp);
//    $picture = base64_encode($image);
    return $picture; //return the base62 string for the current image.
//    fclose($fp);
}
  function delete_directory($dirname) {
                        if (is_dir($dirname))
                            $dir_handle = opendir($dirname);
                        if (!$dir_handle)
                            return false;
                        while ($file = readdir($dir_handle)) {
                            if ($file != "." && $file != "..") {
                                if (!is_dir($dirname . "/" . $file))
                                    unlink($dirname . "/" . $file);
                                else
                                    delete_directory($dirname . '/' . $file);
                            }
                        }
                        closedir($dir_handle);
                        rmdir($dirname);
                        return true;
                    }
  $xmlFile2 = "Docx/word/_rels/document.xml.rels";
                    $reader2 = new XMLReader;
                    $reader2->open($xmlFile2);
                    $repo = array();
                    while ($reader2->read()) {
                        $node_loc = $reader2->localName;
                        if ($reader2->nodeType == XMLREADER::ELEMENT && $reader2->localName == 'Relationship') {
                            $read_content_id = $reader2->getAttribute("Id");
                            $read_content_name = $reader2->getAttribute("Target");
                            $repo[$read_content_id] = $read_content_name;
                        }
                    }
// set location of docx text content file
$reader2->close();
$xmlFile = "docx/word/document.xml";
$reader = new XMLReader;
$reader->open($xmlFile);

// set up variables for formatting
$text = '';
$formatting['bold'] = 'closed';
$formatting['italic'] = 'closed';
$formatting['underline'] = 'closed';
$formatting['header'] = 0;

$for_image = $xmlFile2;
$status = false;
// loop through docx xml dom
while ($reader->read()) {

    // look for new paragraphs
    if ($reader->nodeType == XMLREADER::ELEMENT && $reader->name === 'w:p') {
        // set up new instance of XMLReader for parsing paragraph independantly
        $paragraph = new XMLReader;
        $p = $reader->readOuterXML();
        $paragraph->xml($p);

        // search for heading
        preg_match('/<w:pStyle w:val="(Heading.*?[1-6])"/', $p, $matches);
        switch ($matches[1]) {
            case 'Heading1': $formatting['header'] = 1;
                break;
            case 'Heading2': $formatting['header'] = 2;
                break;
            case 'Heading3': $formatting['header'] = 3;
                break;
            case 'Heading4': $formatting['header'] = 4;
                break;
            case 'Heading5': $formatting['header'] = 5;
                break;
            case 'Heading6': $formatting['header'] = 6;
                break;
            default: $formatting['header'] = 0;
                break;
        }

        // open h-tag or paragraph
        $text .= ($formatting['header'] > 0) ? '<h' . $formatting['header'] . '>' : '<p>';
        $status = false;

        // loop through paragraph dom
        while ($paragraph->read()) {

            $node_loc = $paragraph->localName;
            // look for elements
            if ($paragraph->nodeType == XMLREADER::ELEMENT && $paragraph->name === 'w:r') {
                $node = trim($paragraph->readInnerXML());
                //echo $node;
                // add <br> tags
                if (strstr($node, '<w:br '))
                    $text .= '<br>';

                // look for formatting tags                    
                $formatting['bold'] = (strstr($node, '<w:b/>')) ? (($formatting['bold'] == 'closed') ? 'open' : $formatting['bold']) : (($formatting['bold'] == 'opened') ? 'close' : $formatting['bold']);
                $formatting['italic'] = (strstr($node, '<w:i/>')) ? (($formatting['italic'] == 'closed') ? 'open' : $formatting['italic']) : (($formatting['italic'] == 'opened') ? 'close' : $formatting['italic']);
                $formatting['underline'] = (strstr($node, '<w:u ')) ? (($formatting['underline'] == 'closed') ? 'open' : $formatting['underline']) : (($formatting['underline'] == 'opened') ? 'close' : $formatting['underline']);
                $str = $paragraph->expand()->textContent;
                // build text string of doc
                $text .= (($formatting['bold'] == 'open') ? '<strong>' : '') .
                        (($formatting['italic'] == 'open') ? '<em>' : '') .
                        (($formatting['underline'] == 'open') ? '<u>' : '') .
                        //htmlentities(iconv('UTF-8', 'ASCII//TRANSLIT',$paragraph->expand()->textContent)).
                        htmlentities($str, ENT_QUOTES, "utf-8") .
                        (($formatting['underline'] == 'close') ? '</u>' : '') .
                        (($formatting['italic'] == 'close') ? '</em>' : '') .
                        (($formatting['bold'] == 'close') ? '</strong>' : '');

                // reset formatting variables
                foreach ($formatting as $key => $format) {
                    if ($format == 'open')
                        $formatting[$key] = 'opened';
                    if ($format == 'close')
                        $formatting[$key] = 'closed';
                }
            }
            if ($node_loc == 'blip' && !$status) {
                $attri_r = $paragraph->getAttribute("r:embed");
                $current_image_name = $repo[$attri_r];
                $image_stream = "" . showimage($current_image_name) . ""; //return the base64 string 
                //echo $image_stream;


                $text.="" . '<img height="150" width="200" src="data:image/jpg;base64,' . $image_stream . '">';
                $text.=$image_stream;
                $status = true;
            }
        }
        $text .= ($formatting['header'] > 0) ? '</h' . $formatting['header'] . '>' : '</p>';
    }
}

$text = str_replace("<p></p>", "", $text);


//echo $text;


$html = new simple_html_dom();

$html->load($text);



$arr_all = array();

$q = 0;
 
foreach ($html->find('p') as $element) {
    //echo $i." ".$element->plaintext . '<br>';
    //echo $element;
    $dot  =  explode(".", $element->plaintext);
    if ($element != "") {
        # code...
   
    //echo $dot[0]."<br/>"; 
    if(!is_numeric($dot[0])){
         
        $arr_all [$q]['q'] = $element->plaintext;
        ++$q;
    }
}
}
    $check_b = 'true';
//print_r($arr_all);
   $num_que_new  = sizeof($arr_all);
for ($i = 1; $i < $num_que_new; $i++) {
     $chk_que = substr($arr_all[$i]['q'], 3);
     // echo  $chk_que.'asd<br/>';
       //echo $arr_all[$i]['a']."<br/>";
      // $chk_a = substr($arr_all[$i]['a'], 3);
      // $chk_b = substr($arr_all[$i]['b'], 3);
      // $chk_c = substr($arr_all[$i]['c'], 3);
     //  $chk_d = substr($arr_all[$i]['d'], 3);
        if (empty($chk_que))  {
         // echo $chk_que;
           $check_b = 'false';
           break;
        }
      
    
    # code...
}
?>

<?

$num_que = count($arr_all) / 1;

//echo $num_que;

$que = 0;
//$ans_a = 1;
//$ans_b = 2;
//$ans_c = 3;
//$ans_d = 4;
//$ans = 5;
//$obj = 6;


//mysql_connect($dbhost, $dbuser, $dbpass) or die('cannot connect to the server');
//mysql_select_db($dbname) or die('database selection problem');

   // $subject = $_GET['subject'];
  //  $type = $_GET['type'];
   // $Delet_Record = "DELETE FROM test WHERE type = '".$_GET['type']."' AND id_course = '".$_GET['subject']."'";
    //echo $Delet_Record;
  //  mysql_query($Delet_Record);

if($check_b  == 'true'){
for ($i = 0; $i < $num_que_new;) {
    $chk_que = substr($arr_all[$i]['q'], 3);
   // echo $chk_que;
   
    // $chk_a = substr($arr_all[$i]['a'], 3);
     //  $chk_b = substr($arr_all[$i]['b'], 3);
     //  $chk_c = substr($arr_all[$i]['c'], 3);
    //   $chk_d = substr($arr_all[$i]['d'], 3);
    ?>
    <!--<div class="col-md-6 col-md-offset-3 col-md-offset-3">

    <?php echo "ข้อที่" . $i . ". " . substr($arr_all[$que], 3) . "<BR>"; ?>

    </div>-->

    <?php
    //echo $arr_all[$que]."<BR>";
    //echo $arr_all[$ans_a]."<br>";
    //echo $arr_all[$ans_b]."<br>";
    //echo $arr_all[$ans_c]."<br>";
    //echo $arr_all[$ans_d]."<br>";
    //echo $arr_all[$ans]."<br>";
    //echo "<br><br>";
   
    //$delete = mysql_query($Delet_Record, $bmksl) or die(mysql_error());
$type = $_POST['type'];
     strlen($chk_que);
    if(strlen($chk_que) > 2){
        $chk_que = substr($arr_all[$i]['q'], 4);
        echo $chk_qu;
        //break;
    }
    $i++;
    $ans1  = trim($chk_que);
    $result = mysql_query("SELECT * FROM test where type = '$type'");
    $num_rows = mysql_num_rows($result);
        if (!is_numeric($ans1))
{
    $message = "กรุณาตรวจสอบไฟล์เฉลย คำตอบต้องเป็นเฉพาะตัวเลข 1 2 3 4";
                        echo "<script type='text/javascript'>alert('$message');</script>";
                       
           $check_b = 'false';
           break;
}
  
    


    mysql_query("Set names 'utf8'");
    $sql = "UPDATE test SET ans = '$ans1' WHERE num = $i AND type = '$type'";
    
    //echo $sql;
    mysql_query($sql);
    //echo $sql."<BR>";

    $que += 1;
    //$ans_a += 5;
    //$ans_b += 5;
    //$ans_c += 5;
    //$ans_d += 5;
    //$ans += 7;
    //$obj += 7;
    ?>
    <p style="margin-left:15%;" ><?php echo "ข้อที่" . $i . ". " . $chk_que . "<BR>"; ?></p>
    <?
  
}

if ($num_rows != $i) {
       $message = "กรุณาตรวจสอบไฟล์เฉลย จำนวนเฉลยมีจำนวนไม่เท่ากับข้อสอบ";
                        echo "<script type='text/javascript'>alert('$message');</script>";
                       $sql = "UPDATE test SET ans = '' WHERE  type = '$type'";
    //echo $sql;
    mysql_query($sql);
    ?>
<form align="center" action="new_ans.php" method="POST" style="padding-top: 15px;">
                            <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                            <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                            <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                            <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                            <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                            <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                            <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                            <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/>
                            <input type="hidden" name="type" value="<?= $_POST['type']; ?>"/>
                            <input type="submit" value="ย้อนกลับ  ( Back )  " style="border: none;background: none;color: #2371E2;cursor: pointer;"/>
</form>
    <?
           $check_b = 'false';
           //break;
    }
if ($check_b == "true") {
    $message = "ทำการอัพโหลดไฟล์เฉลยข้อสอบเรียบร้อย";
                        echo "<script type='text/javascript'>alert('$message');</script>";
                       // echo "<script type='text/javascript'>
                      //  window.location.replace('new_test.php');
                  //      </script>";
}
}else{
    $message = "กรุณาตรวจสอบรูปแบบไฟล์ข้อสอบ";
                        echo "<script type='text/javascript'>alert('$message');</script>";
                      //  echo "<script type='text/javascript'>
                      //  window.location.replace('new_test.php');
                    //</script>";

}

mysql_close();

$reader->close();

//unlink($folder.$file_name);
//delete_directory("Docx");
//mkdir("Docx");
//mkdir("Docx/word");
//mkdir("Docx/word/media");
//echo 'UPLOAD AND CONVERT SUCCESS';
// if $res === TRUE
// if btn-upload
?>
<? 
if ($check_b == 'true'){?>



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
                            <input type="submit" value="เพิ่มตัวชี้วัด  ( Add Indicator )  " style="border: 2;background: none;color: #2371E2;cursor: pointer;"/>
</form>
<? } ?>
<script type="text/javascript">
  //window.location.replace('new_test.php')
</script>
</body>
</html>