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
       
                <?php
                error_reporting(0);


                if (isset($_POST['btn-upload'])) {

                    include("simple_html_dom.php");
                    require("class.DOCX-HTML.php");
                    //require ("wp-includes/option.

////////////////////////////////////////////////
//                    function showimage($file_name_image) {
//                        $pic = 'docx/word/' . $file_name_image;
//
//                        $picture = base64_encode(file_get_contents($pic));
//                        echo '<img src="' . $pic . '">';
//                        exit();
////    
//////    echo $zip_file_original = $zip_file_original;
//////    $file_name_image = 'docx/word/' . $file_name_image . ''; // getting the image in the zip using its name
//////    //echo "<br/>";
//////    $z_show = new ZipArchive();
//////    if ($z_show->open($zip_file_original) !== true) {
//////        echo "File not found.";
//////        return false;
//////    }
//////
//////    $stat = $z_show->statName($file_name_image);
//////    $fp = $z_show->getStream($file_name_image);
//////    if (!$fp) {
//////        echo "Could not load image.";
//////        return false;
//////    }
////
////    header('Content-Type: image/jpeg');
////    header('Content-Length: ' . $stat['size']);
////    $image = stream_get_contents($fp);
////    $picture = base64_encode($image);
//                        return $picture; //return the base62 string for the current image.
////    fclose($fp);
//                    }

                  
//var_dump($repo);
/////////////////////////////////////////
//$extract = new DOCXtoHTML();
//$extract->docxPath = $_FILES['file']['tmp_name'];
//$extract->content_folder = strtolower(str_replace(".".$path_info['extension'],"",str_replace(" ","-",$path_info['basename'])));
//$extract->image_max_width = get_option('docxhtml_max_image_width');
//$extract->imagePathPrefix = plugins_url();
//$extract->keepOriginalImage = ($_POST['docxhtml_original_images']=="true") ? true:false;
//echo $post_data;

                    $file_name = $_FILES['file']['name'];
                    $file_loc = $_FILES['file']['tmp_name'];
                    $file_size = $_FILES['file']['size'];
                    $file_type = strrchr($file_name,".");
                    $folder = "files/";
                    $folderdocx = "Docx/word/";
                    $folder1 = "image/" . $file_name;

                    if(($file_type==".doc")||($file_type==".docx"))
                    {
                    
                     move_uploaded_file($file_loc, $folder . $file_name);
                    }
                    else{
                        $message = "ชนิดของไฟล์ข้อสอบไม่ถูกต้อง";
                        echo "<script type='text/javascript'>alert('$message');</script>";
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
                        break;
                    }


                    $zip = new ZipArchive;
                    $res = $zip->open("files/" . $file_name);
                    if ($res === TRUE) {
                        // echo 'ok';
                        $zip->extractTo('Docx');
                        $zip->close();
                    }
                    ?>
 <form align="center" action="upload_test_3.php?subject=<? echo $_GET['subject']?>&year=<?php echo $_GET['year'];?>&type=<? echo "M"?> " method="POST" style="padding-top: 15px;">
                                    <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    <input type="submit" value="เพิ่มข้อสอบ  ( Add_Test )  " style="border: 2;background: none;color: #2371E2;cursor: pointer;"/>
                                </form> 
                    <script type="text/javascript">
                        //window.location.replace('upload_test_3.php?subject=<? echo $_GET['subject']?>&year=<?php echo $_GET['year'];?>&type=<? echo "M"?>');
                    </script>
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
                    <?php
                exit(); }
/////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    if (isset($_POST['btn-upload2'])) {

                    include("simple_html_dom.php");
                    require("class.DOCX-HTML.php");

              
                
                    //require ("wp-includes/option.

////////////////////////////////////////////////
//                    function showimage($file_name_image) {
//                        $pic = 'docx/word/' . $file_name_image;
//
//                        $picture = base64_encode(file_get_contents($pic));
//                        echo '<img src="' . $pic . '">';
//                        exit();
////    
//////    echo $zip_file_original = $zip_file_original;
//////    $file_name_image = 'docx/word/' . $file_name_image . ''; // getting the image in the zip using its name
//////    //echo "<br/>";
//////    $z_show = new ZipArchive();
//////    if ($z_show->open($zip_file_original) !== true) {
//////        echo "File not found.";
//////        return false;
//////    }
//////
//////    $stat = $z_show->statName($file_name_image);
//////    $fp = $z_show->getStream($file_name_image);
//////    if (!$fp) {
//////        echo "Could not load image.";
//////        return false;
//////    }
////
////    header('Content-Type: image/jpeg');
////    header('Content-Length: ' . $stat['size']);
////    $image = stream_get_contents($fp);
////    $picture = base64_encode($image);
//                        return $picture; //return the base62 string for the current image.
////    fclose($fp);
//                    }

                  
//var_dump($repo);
/////////////////////////////////////////
//$extract = new DOCXtoHTML();
//$extract->docxPath = $_FILES['file']['tmp_name'];
//$extract->content_folder = strtolower(str_replace(".".$path_info['extension'],"",str_replace(" ","-",$path_info['basename'])));
//$extract->image_max_width = get_option('docxhtml_max_image_width');
//$extract->imagePathPrefix = plugins_url();
//$extract->keepOriginalImage = ($_POST['docxhtml_original_images']=="true") ? true:false;
//echo $post_data;

                   $file_name = $_FILES['file']['name'];
                    $file_loc = $_FILES['file']['tmp_name'];
                    $file_size = $_FILES['file']['size'];
                    $file_type = strrchr($file_name,".");
                    $folder = "files/";
                    $folderdocx = "Docx/word/";
                    $folder1 = "image/" . $file_name;

                    if(($file_type==".doc")||($file_type==".docx"))
                    {
                    
                     move_uploaded_file($file_loc, $folder . $file_name);
                    }
                    else{
                        $message = "ชนิดของไฟล์ข้อสอบไม่ถูกต้อง";
                        echo "<script type='text/javascript'>alert('$message');</script>";
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
                        break;
                    }
                   
/////////////////////////////////////////
                    $zip = new ZipArchive;
                    $res = $zip->open("files/" . $file_name);
                    if ($res === TRUE) {
                        // echo 'ok';
                        $zip->extractTo('Docx');
                        $zip->close();
                    }
                    ?>
<form align="center" action="upload_test_3.php?subject=<? echo $_GET['subject']?>&year=<?php echo $_GET['year'];?>&type=<? echo "F"?> " method="POST" style="padding-top: 15px;">
                                    <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                                    <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                                    <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                                    <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                                    <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                                    <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                                    <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                                    <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
                                    <input type="submit" value="เพิ่มข้อสอบ  ( Add_Test )  " style="border: 2;background: none;color: #2371E2;cursor: pointer;"/>
                                </form>
                    <script type="text/javascript">
                        //window.location.replace('upload_test_3.php?subject=<? echo $_GET['subject']?>&year=<?php echo $_GET['year'];?>&type=<? echo "F"?>');
                    </script>
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
                    <?php
                exit(); }
                    ?>



    </body>
</html>
