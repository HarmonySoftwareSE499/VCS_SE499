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
                <td><b>ขนิดการสอบ</b>&nbsp;<span class="eng">(Type Exam)</span>: <?= $_POST['type']; ?></td>
                <td></td>
            </tr>
        </table>  
<h2 align="center">ข้อสอบที่นำเข้าทั้งหมด</h2>
        <br/>
<?
function showimage($file_name_image) {
    $pic = 'Docx/word/' . $file_name_image;

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
$xmlFile = "Docx/word/document.xml";
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
                //echo $image_stream.$current_image_name."asdasdasd";


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

$array_p  = $html->find('p');

$arr_all = array();

$q = 0;
 
foreach ($array_p  as $keys=>$element) {
    //echo $i." ".$element->plaintext . '<br>';
    //echo $element;
        //echo $keys."<br/>";

    $dot  =  explode(".", $element->plaintext);
    if ($element != "") {
        # code...
   
    //echo  ($dot[0])."<br/>"; 
    if(!is_numeric($dot[0])){
         
         $arr_all [$q]['q'] = $element->plaintext;
         $ch_quetion = $arr_all [$q]['q'].'<br>';
        ++$q;

    }else{
         //echo $q;
        if($dot[0]=='1'){
             $arr_all [$q-1]['a'] = $element->plaintext;
        }else if($dot[0]=='2'){
              $arr_all [$q-1]['b'] = $element->plaintext;
        }else if($dot[0]=='3'){
              $arr_all [$q-1]['c'] = $element->plaintext;
        }else if($dot[0]=='4'){
              $arr_all [$q-1]['d'] = $element->plaintext;
             
        }else{
           //error
           //echo  $array_p[$keys];
          // exit(); 
        }
    }

 }
    
}


//print_r($arr_all);
$check_qq = ture;
$num_q = 0;
foreach ($arr_all as $key => $value) {
    # code...
    $ex = explode(").",$value['q']);
    $num_q++;
    //echo $num_q.'<BR>';
    if($num_q!=$ex[0] || $ex[0]== ""){
        //error
        //echo $value['q']."  ".$num_q.$value['q'].'<BR>';
        echo "กรุณาตรวจสอบรูปแบบไฟล์ข้อสอบ ข้อที่ ".$num_q;
        ?>
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
       //echo $num_q; // lose question.. 
        exit();
    }

    $exc1 = explode(".",$value['a']);
    
    if($exc1[0] != "1"){
        //error
        echo "กรุณาตรวจสอบตัวเลือกที่ 1 ข้อ ".($num_q).'<BR>'; // lose question.. 
        ?>
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
        exit();
    }
      $exc2 = explode(".",$value['b']);
    
    if($exc2[0] != "2"){
        //error
          echo "กรุณาตรวจสอบตัวเลือกที่ 2 ข้อ ".($num_q).'<BR>'; // lose question.. 
        ?>
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
        exit();
    }
      $exc3 = explode(".",$value['c']);
    
    if($exc3[0] != "3"){
        //error
         echo "กรุณาตรวจสอบตัวเลือกที่ 3 ข้อ ".($num_q).'<BR>'; // lose question.. 
        ?>
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
        exit();
    }
      $exc4 = explode(".",$value['d']);
    
    if($exc4[0] != "4"){
        //error
         echo "กรุณาตรวจสอบตัวเลือกที่ 4 ข้อ ".($num_q).'<BR>'; // lose question.. 
        ?>
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
        exit();
    }



}

$check_b = 'true';
//exit();
 $num_que_new  = sizeof($arr_all);

for ($i = 0; $i < $num_que_new; $i++) {
//echo  $chk_que.'asd<br/>';
       //echo $arr_all[$i]['q']."<br/>";
       //echo $arr_all[$i]['a']."<br/>";
       //echo $arr_all[$i]['b']."<br/>";
       //echo $arr_all[$i]['c']."<br/>";
       //echo $arr_all[$i]['d']."<br/>";
      $chk_que = substr($arr_all[$i]['q'], 3) ;
      
        $chk_a = substr($arr_all[$i]['a'], 3) ;
       $chk_b = substr($arr_all[$i]['b'], 3);
       $chk_c = substr($arr_all[$i]['c'], 3);
       $chk_d = substr($arr_all[$i]['d'], 3);

        if (empty($chk_que) || empty($chk_a) || empty($chk_b) || empty($chk_c) || empty($chk_d))  {
          //echo $chk_que;
           // $i = $i+1;
             //echo  "*ข้อที่ ".$i.'<font color="red">รูปแบบไม่ถูกต้องกรุณาตรวจเช็ค</font>';
           $check_b = 'false';
        
           break;
        }

    
    # code...
}
?>

<?

$num_que = count($arr_all) / 5;

//echo $num_que;

$que = 0;
$ans_a = 1;
$ans_b = 2;
$ans_c = 3;
$ans_d = 4;
//$ans = 5;
//$obj = 6;


//mysql_connect($dbhost, $dbuser, $dbpass) or die('cannot connect to the server');
//mysql_select_db($dbname) or die('database selection problem');
$subject = $_POST['subject'];
$type = $_POST['type'];
$year = $_POST['year'];

?>
<!--<form align="center" action="new_test.php" method="POST" style="padding-top: 15px;">
                            <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                            <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                            <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                            <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                            <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                            <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                            <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                            <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                            <input type="submit" value="กลับสู่หน้าหลัก  ( Back )  " style="border: none;background: none;color: #2371E2;cursor: pointer;"/>
</form>-->
<?
if ($check_b == 'true' ) {
?> 


<?
    if (empty($chk_que) || empty($chk_a) || empty($chk_b) || empty($chk_c) || empty($chk_d)) {
           $message = "กรุณาตรวจสอบรูปแบบไฟล์ข้อสอบ";
                        echo "<script type='text/javascript'>alert('$message');</script>";
                       
           $check_b = 'false';

           exit();
       }
    $Delet_Record = "DELETE FROM question WHERE Id_Issue = '".$_POST['Id_Issue']."' ";
    
    //echo $Delet_Record;
    mysql_query($Delet_Record); 
}
 





//echo $check_b;
if($check_b  == 'true'){
for ($i = 0; $i < $num_que_new; ) {
    $chk_que = substr($arr_all[$i]['q'], 3);
     $chk_a = substr($arr_all[$i]['a'], 3);
       $chk_b = substr($arr_all[$i]['b'], 3);
       $chk_c = substr($arr_all[$i]['c'], 3);
       $chk_d = substr($arr_all[$i]['d'], 3);
       
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
strlen($chk_que);
    if(strlen($chk_que) > 2){
        $chk_que = substr($arr_all[$i]['q'], 4);
        //echo $chk_qu;
        //break;
    }
    if($check_b == 'true'){
   $i++;
    mysql_query("Set names 'utf8'");
    $Id_Issue = $_POST['Id_Issue'];
    $sql = "INSERT INTO question (IDtest,Id_Issue,num,text1,c1,c2,c3,c4) 
      VALUES ('','$Id_Issue','$i','" .$chk_que. "','" .$chk_a. "','" .$chk_b. "','" .$chk_c. "','" .$chk_d. "'
        )";
    
    mysql_query($sql);
}
    //echo $sql."<BR>";

if (strlen($chk_que) > 10000 ) {
                             //$pic = explode("/", $chk_que);
                             //echo $pic[0];
                            //$a1 = ereg_replace($pic[0], "", $chk_que);
                           // echo $a1;
                             ?>

<?  
   $chk_que1 =  explode("/9j/", $chk_que);
    //print_r($chk_que1); 
    //echo $chk_que1[0];
    //echo $chk_que1[1];
    //echo $chk_que1[2];
?>
                             <div style="margin-left:20%;"><? echo $i. "."." ".$chk_que1[0];?><img style="width:80px; float:none;" src="data:image/jpg;base64,/9j/<?=$chk_que1[1].$chk_que1[2]?>"></div><BR><?
                           }else{?>
                           <div style="margin-left:20%;"><p><? echo "$i ".".".$chk_que."<BR>";?></p></div> 
                                <?}
    
    //$que += 5;
   // $ans_a += 5;
    //$ans_b += 5;
   // $ans_c += 5;
//$ans_d += 5;
    //$ans += 7;
    //$obj += 7;
    
                        
}
 if ($check_b == "true") {
        $message = "ทำการอัพโหลดไฟล์ข้อสอบเสร็จเรียบร้อย";
                        echo "<script type='text/javascript'>alert('$message');</script>";
                       //   echo "<script type='text/javascript'>
                        ?>
 <form  align="center" action="new_ans.php" method="POST">
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
                                    <input type="submit" value="นำเข้าเฉลย (Add Answer)" style="border: 2;background: none;color: #2371E2;cursor: pointer;"/>
                                </form>
<form align="center" action="new_course_display_detail_test.php" method="POST" style="padding-top: 15px;">
                                    <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    <input type="hidden" name="type" value="<?= $_POST['type']; ?>"/>
                                    <input type="submit" value="กลับสู่หน้าหลัก  ( Back )  " style="border: 2;background: none;color: #2371E2;cursor: pointer;"/>
                                </form>

                        <?
                       // window.location.replace('new_test.php');
                    //</script>";# code...
    }   

}else{
    $message = "กรุณาตรวจสอบรูปแบบไฟล์ข้อสอบ";
                       echo "<script type='text/javascript'>alert('$message');</script>";
                      // echo "<script type='text/javascript'>
                       // window.location.replace('new_test.php');
                    //</script>";
                       ?>
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
                    exit();

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
<!--<form align="center" action="new_test.php" method="POST" style="padding-top: 15px;">
                            <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                            <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                            <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                            <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                            <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                            <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                            <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                            <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                            <input type="submit" value="กลับสู่หน้าหลัก  ( Back )  " style="border: none;background: none;color: #2371E2;cursor: pointer;"/>
</form>-->

<script type="text/javascript">
  //window.location.replace('new_test.php')
</script>
</body>
</html>