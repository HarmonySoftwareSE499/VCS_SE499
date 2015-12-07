<?
session_start();
?>
<? ob_start(); ?>
<?php require_once('Connections/bmks.php'); ?>
<?php require_once('Connections/bmksl.php'); ?>
<? require_once 'mpdf60/mpdf.php'; ?>
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
$subone_name = "เลือกข้อสอบ (Choose Test)"; //หัวข้อหลัก
$subtwo_name = ""; //หัวข้อย่อย
//- See more at: http://sixhead.com/2008/09/28/easy-export-to-microsoft-word/#sthash.tgoLHWHZ.dpuf
?>
<html xmlns:o="urn:schemas-microsoft-com:office:office"
      xmlns:x="urn:schemas-microsoft-com:office:word"
      xmlns="http://www.w3.org/TR/REC-html40">

    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <head>
    <style type="text/css">
    body{
        font-size: 14px;
    }
    table tr td {
        font-size: 10px;
    }
    div {
font-size: 10px;

    }
    </style>

    </head>
    <body>

        <?
        $Id_New_Test = $_GET['Id_New_Test'];
        $strSQL = "SELECT *
FROM subject
INNER JOIN new_test
ON subject.subjectID=new_test.subjectID Where Id_New_Test = $Id_New_Test ;";
//$strSQL = "SELECT * FROM new_test where Id_New_Test = $Id_New_Test ";
        $objQuery = mysql_query($strSQL) or die("Error Query [" . $strSQL . "]");
        while ($objResult = mysql_fetch_array($objQuery)) {
            ?>
            <?
            $term = $objResult['term'];
            substr($term, 9);
            ?>
            <div align="center">
                <img width="100px" height="80px"  src="img/varee_logo.jpg">
            </div>
            <p align="center"><b>โรงเรียนวารีเชียงใหม่</b><br>
                    ข้อสอบวิชา <? echo $objResult['SCODE'] . "\t"; ?>(<? echo $objResult['SNAME'] . "-" . $objResult['TSNAME']; ?>)    <? echo $objResult['score']; ?> คะแนน สอบ<? echo $objResult['type'] . substr($term, 9) . "/" . $objResult['year']; ?><br>
                        ระดับชั้น<? echo $objResult['level']; ?>    เวลา <? echo $objResult['time']; ?> นาที </p>
                        <hr width="100%" size="20" color="black"><p>
                                <?
                                $strSQL1 = "SELECT DISTINCT *
FROM new_test 
INNER JOIN reference_test
ON new_test.Id_New_Test= reference_test.Id_New_Test
INNER JOIN db_test 
ON reference_test.IDtest = db_test.IDtest
WHERE reference_test.Id_New_Test = $Id_New_Test
";
                                $objQuery1 = mysql_query($strSQL1);
                                ?>

                                <div>

                                    <table width="100%" style="font-size:14px;" class="table table-condensed"  >
                                        <tr width="100%">      
                                        </tr>
                                        <?
                                        $i = 1;
                                        while ($objResult1 = mysql_fetch_array($objQuery1)) {
                                            $chk_que1 =  explode("/9j/", $objResult1["text1"]);
                                            //echo $objResult1['text1'];
                                            ?>

                                            <tr>
                                                <td width="100%"><?
                                                    echo $i . " ). ".$chk_que1[0].'<BR>';
                                                    //echo $objResult1["text1"]."<BR>"; 
                                                    if (strlen($objResult1["text1"]) > 5000) {
                                                        ?><?
                                                        $data = $chk_que1[1].$chk_que1[2];
                                                        $type = "jpg";
                                                        $base64 = 'data:image/' . $type . ';base64,' . "/9j/".$data;

                                                        base64_to_jpeg($base64, 'text'.$i.'.jpg');
                                                        ?><img src='img_buffer_test/text<?=$i?>.jpg' width="80" /><BR><?
                                                            } else {
                                                                echo $objResult1["text1"] . "<BR>";
                                                            }
                                                            if (strlen($objResult1["c1"]) > 5000) {
                                                                ?>1. <?
                                                                $data = $objResult1["c1"];
                                                                $type = "jpg";
                                                                $base64 = 'data:image/' . $type . ';base64,' . "/9j/".$data;
                                                                base64_to_jpeg($base64, 'c1_'.$i.'.jpg');
                                                                ?><img src='img_buffer_test/c1_<?=$i?>.jpg' width="80"><BR><?
                                                                    } else {
                                                                        echo '&nbsp;&nbsp;' . "1. " . $objResult1["c1"] . "<BR>";
                                                                    }
                                                                    if (strlen($objResult1["c2"]) > 5000) {
                                                                        ?>2. <?
                                                                        $data = $objResult1["c2"];
                                                                        $type = "jpg";
                                                                        $base64 = 'data:image/' . $type . ';base64,' . "/9j/".$data;
                                                                        base64_to_jpeg($base64, 'c2_'.$i.'.jpg');
                                                                        ?><img src='img_buffer_test/c2_<?=$i?>.jpg' width="80" /><BR><?
                                                                            } else {
                                                                                echo '&nbsp;&nbsp;' . "2. " . $objResult1["c2"] . "<BR>";
                                                                            }
                                                                            if (strlen($objResult1["c3"]) > 5000) {
                                                                                ?>3. <?
                                                                                $data = $objResult1["c3"];
                                                                                $type = "jpg";
                                                                                $base64 = 'data:image/' . $type . ';base64,' . "/9j/".$data;
                                                                                base64_to_jpeg($base64, 'c3_'.$i.'.jpg');
                                                                                ?><img src='img_buffer_test/c3_<?=$i?>.jpg' width="80"><BR><?
                                                                                    } else {
                                                                                        echo '&nbsp;&nbsp;' . "3. " . $objResult1["c3"] . "<BR>";
                                                                                    }if (strlen($objResult1["c4"]) > 5000) {
                                                                                        ?>4. <?
                                                                                        $data = $objResult1["c4"];
                                                                                        $type = "jpg";
                                                                                        $base64 = 'data:image/' . $type . ';base64,' . "/9j/".$data;
                                                                                        base64_to_jpeg($base64, 'c4_'.$i.'.jpg');
                                                                                        ?><img src='img_buffer_test/c4_<?=$i?>.jpg' width="80"><BR><?
                                                                                            } else {
                                                                                                echo '&nbsp;&nbsp;' . "4. " . $objResult1["c4"] . "<BR>";
                                                                                            }

                                                                                            $i++;
                                                                                            echo "<BR><BR>";
                                                                                            ?></td>
                                                                                            </tr>

                                                                                            <?php
                                                                                        }
                                                                                        ?>
                                                                                        </table>

                                                                                        <?
                                                                                    }
                                                                                    ?>

                                                                                    </body>
                                                                                    </html>
                                                                                    <script type="text/javascript">
                                                                                        //window.print();

                                                                                    </script>

                                                                                    <?

                                                                                    function base64_to_jpeg($base64_string, $output_file) {
                                                                                        $ifp = fopen("img_buffer_test/" . $output_file, "wb");
                                                                                       // echo $base64_string.'<BR>'; exit();  
                                                                                        $data = explode(',', $base64_string);

                                                                                        fwrite($ifp, base64_decode($data[1]));
                                                                                        fclose($ifp);

                                                                                        return $output_file;
                                                                                    }
                                                                                    ?>


                                                                                    <?Php
                                                                                    //exit();
                                                                                      $html = ob_get_contents();
                                                                                    ob_end_clean();
                                                                                    set_time_limit(0);
                                                                                    ini_set('memory_limit','512M');
                                                                                     
                                                                                    $mpdf = new mPDF('th', 'A4', '0', 'THSaraban');
//
                                                                                    $mpdf->WriteHTML($html);
//
                                                                                    $mpdf->Output();
                                                                                    ?>
