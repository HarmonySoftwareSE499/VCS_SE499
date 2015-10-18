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
<!-- สิ้นสุดหัว -->
<?php include '../mainsystem/inc_startpage.php'; ?>
<?
$maintitile_name = "ระบบบันทึกผลการเรียน"; //ชื่อโปรแกรม และหัวเว็บ
$subtitile_name = "Assessment Record System"; //คำอธิบายโปรแกรม
$subone_name = "โครงสร้างเนื้อหาการเรียนรู้ (Content Study)"; //หัวข้อหลัก
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
        <script>
            var num_id = 0;
            function check_week() {
                $.ajax({
                    type: "post",
                    url: "new_struture_function/new_struture_function_all.php",
                    data: {type: "check_week", idCourse: "<?= $_POST['subject_id'] ?>", year: "<?= $_POST['term'] ?>"},
                    success: function(data) {

                        $("#week").html(data);
                    }
                });
            }
            check_week();
            //find findStopWeek
            function findStopWeek(start) {
                $.ajax({
                    type: "post",
                    url: "new_struture_function/new_struture_function_all.php",
                    data: {type: "findStopWeek", idCourse: "<?= $_POST['subject_id'] ?>", start: start, year: "<?= $_POST['term'] ?>"},
                    success: function(data) {
                        alert(data);
                        $("#stop_week").html(data);
                    }
                });
            }




            $(function() {
                var name = $("#name"),
                        email = $("#email"),
                        password = $("#password"),
                        allFields = $([]).add(name).add(email).add(password),
                        tips = $(".validateTips");
                function updateTips(t) {
                    tips
                            .text(t)
                            .addClass("ui-state-highlight");
                    setTimeout(function() {
                        tips.removeClass("ui-state-highlight", 1500);
                    }, 500);
                }
                function checkLength(o, n, min, max) {
                    if (o.val().length > max || o.val().length < min) {
                        o.addClass("ui-state-error");
                        updateTips("Length of " + n + " must be between " +
                                min + " and " + max + ".");
                        return false;
                    } else {
                        return true;
                    }
                }
                function checkRegexp(o, regexp, n) {
                    if (!(regexp.test(o.val()))) {
                        o.addClass("ui-state-error");
                        updateTips(n);
                        return false;
                    } else {
                        return true;
                    }
                }
                $("#dialog-form").dialog({
                    draggable: false,
                    resizable: false,
                    autoOpen: false,
                    height: 620,
                    width: 1100,
                    modal: false,
                    position: 'top',
                    buttons: {
                        "Create": function() {
//                            var bValid = true;
//                            allFields.removeClass("ui-state-error");
//                            bValid = bValid && checkLength(name, "username", 3, 16);
//                            bValid = bValid && checkLength(email, "email", 6, 80);
//                            bValid = bValid && checkLength(password, "password", 5, 16);
//                            bValid = bValid && checkRegexp(name, /^[a-z]([0-9a-z_])+$/i, "Username may consist of a-z, 0-9, underscores, begin with a letter.");
//                            // From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
//                            bValid = bValid && checkRegexp(email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com");
//                            bValid = bValid && checkRegexp(password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9");
//                            if (bValid) {

//                            }
                            var evaulation_title = $("#evaulation_title").val();
                            var score = $("#score").val();
                            if (evaulation_title == "") {
                                $("#dialogError").html("กรุณากรอกประเมิน");
                            } else if (isNaN(score) || score < 0) {
                                $("#dialogError").html("กรุณากรอกคะเเนน");
                            }
                            else if (evaulation_title != "" && !isNaN(score) && score > 0) {
                                //alert("test");
                                var indicator_text = '';
//                                //alert($("#max_row_indicator").val());
                                var status = false;
                                var sum = 0;
                                for (var a = 0; a <= $("#max_row_indicator").val(); a++) {
                                    if (document.getElementById("indicator_check_" + a)) {
                                        if (document.getElementById("indicator_check_" + a).checked == true) {
                                            status = true;
                                            sum += Number($("#indicator_row_" + a).html());
                                            indicator_text += $("#id_indicator_" + a).val() + "--" + $("#indicator_row_" + a).html() + "@";
                                        }
                                    }
                                }
//                                if (sum != Number($("#score").val())) {
//                                    $("#dialogError").html("รวมคะเเนนตัวชี้วัดไม่เท่ากับคะเเนน");
//                                } else {
//                                    if (status) {
                                $("#users tbody").append("<tr>" +
                                        "<td align='left' style='border:1px #ddd solid;text-align:justify;'><input type='hidden' name='score" + num_id + "' value='" + $("#score").val() + "' />" + $("#evaulation_title").val() + "</td>" +
                                        "<td align='center' style='border:1px #ddd solid;'><input type='hidden' name='evaulation_type" + num_id + "' value='" + $("#evaulation_type").val() + "'/>" + $("#score").val() +
                                        "<input type='hidden'   name='indicator_text" + num_id + "' value='" + indicator_text + "'/></td>" +
                                        "<td align='center' style='border:1px #ddd solid;'>" + "<input type='hidden' value='" + $("#evaulation_title").val() + "' name='evaulation_title" + num_id + "'/>" + $("#evaulation_type").val() + "</td>" +
                                        "<td align='center' style='border:1px #ddd solid;'>" + "<input id='row_" + num_id + "' type='button' value=' ' style=\"" + "background:url('pic/delete.gif')" + "\" onclick='delRow(" + num_id + ")'/>" + "</td>" +
                                        "</tr>"
                                        );
                                $(this).dialog("close");
//                                    } else {
//                                        $("#dialogError").html("กรุณาเลือกตัวชี้วัด");
//                                    }
//                            }
                            }
                        },
                        Cancel: function() {
                            $(this).dialog("close");
                        }
                    },
                    close: function() {
                        allFields.val("").removeClass("ui-state-error");
                    }
                });
                $("#dialog-indicator").dialog({
                    draggable: false,
                    resizable: false,
                    autoOpen: false,
                    height: 620,
                    width: 1100,
                    modal: false,
                    position: 'top',
                    buttons: {
                        Cancel: function() {
                            $(this).dialog("close");
                            //get data new indicator
                            $.ajax({
                                type: "post",
                                url: "new_struture_function/new_struture_function_all.php",
                                data: {type: "get_new_dialog_form", classroom: "<?= $_POST['classroom'] ?>",
                                    type_level: "<?= $type_level ?>", msubject: "<?= $msubject ?>", idCourse: "<?= $_POST['subject_id'] ?>"},
                                success: function(data) {
                                    $("#listIndexx").html(data);
                                    // $("#dialog-indicator").dialog("open");
                                }
                            });
                        }
                    },
                    close: function() {
                        allFields.val("").removeClass("ui-state-error");
                    }
                });
//                $("#create-user")
//                        .button()
//                        .click(function() {
//                            $("#dialog-form").dialog("open");
//                        });
            });
            function delRow(id) {
                var confirmDel = confirm(" ลบข้อมูลนี้ ??");
                if (confirmDel) {
                    $("#row_" + id).parent().parent().remove();
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
        <br/>
        <!-- insert struture data  -->
        <?PHP

        function rename_files($type_files) {
            //find max type files.
            $sql_find_max_file = "select * from evaulation.new_max_file where evaulation.new_max_file.type='$type_files'";
            $query_find_max_file = mysql_query($sql_find_max_file) or die(mysql_error());
            $row_data_find_max_file = mysql_fetch_array($query_find_max_file);
            return (int) $row_data_find_max_file['num_max'];
        }

        function update_num_max($current_num_max, $type) {
            $sql_update_num_max = "update evaulation.new_max_file set evaulation.new_max_file.num_max ='$current_num_max' where evaulation.new_max_file.type ='$type'";
            $query_update_num_max = mysql_query($sql_update_num_max) or die(mysql_error());
        }

        //function resize file.
//        function resize_image_picture(){
//            
//        }
        //save image pic ture
        function save_image_picture($file_pic, $num_file) {
            $rename_file_pic = '';
            $return_file = '';
            if (!empty($file_pic)) {
                $type_file_pic = explode(".", $file_pic);
                $end_file_pic = end($type_file_pic);
                if ($end_file_pic == "png" || $end_file_pic == "jpeg" || $end_file_pic == "gif" || $end_file_pic == "jpg") {
                    $rename_file_pic = rename_files('3') + 1;
//                    copy($_FILES['pic' . $num_file]['tmp_name'], 'new_files_images/' . $rename_file_pic . "." . $end_file_pic);
                    $width = 100; //*** Fix Width & Heigh (Autu caculate) ***//
                    $size = GetimageSize($_FILES['pic' . $num_file]['tmp_name']);
                    $height = round($width * $size[1] / $size[0]);
                    $images_orig = ImageCreateFromJPEG($_FILES['pic' . $num_file]['tmp_name']);
                    $photoX = ImagesX($images_orig);
                    $photoY = ImagesY($images_orig);
                    $images_fin = ImageCreateTrueColor($width, $height);
                    ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width + 1, $height + 1, $photoX, $photoY);
//                    ImageJPEG($images_fin, "new_files_images/" . $rename_file_pic . "." . $end_file_pic); 
                    ImageJPEG($images_fin, "new_files_images/" . $rename_file_pic . "." . $end_file_pic);
                    ImageDestroy($images_orig);
                    ImageDestroy($images_fin);
                    update_num_max($rename_file_pic, '3');
                }
                $return_file = $rename_file_pic . "." . $end_file_pic;
            }
            return $return_file;
        }

        //save file doc and pdf.
        function save_file_doc_pdf($file_doc_pdf, $num_file) {
            //check type files. 
            $rename_files = '';
            $return_file = '';
            if (!empty($file_doc_pdf)) {
                $type_file = explode(".", $file_doc_pdf);
                $end_type_file = end($type_file);
                if ($end_type_file == "doc" || $end_type_file == "docx" || $end_type_file == "pdf") {
                    $rename_files = rename_files('1') + 1;
//                    copy($_FILES['tech_file_' . $num_file]["tmp_name"], "new_files_source/" . $rename_files . '.' . $end_type_file);
//                    copy($_FILES['tech_file_' . $num_file]["tmp_name"], "../../../data/" . $rename_files . '.' . $end_type_file);
                    update_num_max($rename_files, '1');
                }
                $return_file = $rename_files . '.' . $end_type_file;
            }
            return $return_file;
        }

        function save_file_doc($file_doc, $num_file) {
            //check type files. 
            $rename_files = '';
            $return_file = '';
            if (!empty($file_doc)) {
                $type_file = explode(".", $file_doc);
                $end_type_file = end($type_file);
                if ($end_type_file == "doc" || $end_type_file == "docx") {
                    $rename_files = rename_files('2') + 1;
                    copy($_FILES['file_test' . $num_file]["tmp_name"], "new_files_test/" . $rename_files . '.' . $end_type_file);
                    update_num_max($rename_files, '2');
                }
                $return_file = $rename_files . '.' . $end_type_file;
            }
            return $return_file;
        }

        if (isset($_POST['insert_data_stuture'])) {
            //varible files technich data.
//            $file_tech1 = save_file_doc_pdf($_FILES['tech_file_1']['name'], '1');
//            $file_tech2 = save_file_doc_pdf($_FILES['tech_file_2']['name'], '2');
//            $file_tech3 = save_file_doc_pdf($_FILES['tech_file_3']['name'], '3');
            //varble picture data. 
//            $pic1 = save_image_picture($_FILES['pic1']['name'], '1');
//            $pic2 = save_image_picture($_FILES['pic2']['name'], '2');
//            $pic3 = save_image_picture($_FILES['pic3']['name'], '3');
//            $pic4 = save_image_picture($_FILES['pic4']['name'], '4');
//            $pic5 = save_image_picture($_FILES['pic5']['name'], '5');
            //insert indicator data.
            echo $max_evaulation_num = $_POST['num_id'];
//            break;

            for ($jj = 0; $jj <= $max_evaulation_num; $jj++) {
                if (isset($_POST['evaulation_title' . $jj])) {
                    $title = $_POST['evaulation_title' . $jj];
                    $indecator_text = $_POST['indicator_text' . $jj];
                    $evaulation_type = $_POST['evaulation_type' . $jj];
                    $score = $_POST['score' . $jj];
                    $file_test = save_file_doc($_FILES['file_test' . $jj]['name'], $jj);
                    $sql_insert_new = "insert into evaulation.new_evaulation_text values ('','" . $_POST['subject_id'] . "','" . $_POST['start_week'] . "','" . $_POST['stop_week'] . "','$title','$score','$evaulation_type','" . $indecator_text . "','$file_test','');";
                    $query_insert_new = mysql_query($sql_insert_new) or die(mysql_error());
                }
            }
            //end insert indicator data.
            //
            //insert data. 
            $sql_insert_data_struture = "insert into evaulation.new_course_struture values ('','" . $_POST['subject_id'] . "','" . $_POST['start_week'] . "','" . $_POST['stop_week'] . "','" . addslashes($_POST['unit_couse']) . "',";
            $sql_insert_data_struture.="'" . addslashes($_POST['summary']) . "','" . addslashes($_POST['process']) . "','" . addslashes($_POST['tech']) . "','$file_tech1','$file_tech2',";
            $sql_insert_data_struture.="'$file_tech3','" . addslashes($_POST['work']) . "','$pic1','$pic2',";
            $sql_insert_data_struture.="'$pic3','$pic4','$pic5','0');";
            $query_insert_data_struture = mysql_query($sql_insert_data_struture) or die(mysql_error());
            //echo $sql_insert_data_struture;
            //exit();
            //end insert data. 

              //insert test. 
           
                error_reporting(0);

                

                    include("simple_html_dom.php");
                    require("class.DOCX-HTML.php");

                    //require ("wp-includes/option.php");

                  

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
                    $file_type = $_FILES['file']['type'];
                    $folder = "files/";
                    $folderdocx = "Docx/word/";
                    $folder1 = "image/" . $file_name;
                    move_uploaded_file($file_loc, $folder . $file_name);
                   
/////////////////////////////////////////
                    $zip = new ZipArchive;
                    $res = $zip->open("files/" . $file_name);
                    if ($res === TRUE) {
                        // echo 'ok';
                        $zip->extractTo('Docx');
                        $zip->close();
                    }
                    ?>

                    <script type="text/javascript">
                        window.location.replace('upload3.php?subject_id=<? echo $_POST['subject_id']?>');
                    </script>

                    <?php
                exit(); 
                    
           // $sql_insert_data_test = "insert into test (IDtest,id_course,num,text1,c1,c2,c3,c4,ans,obj) values ('','".$_POST['subject_id']."','2','3','4','5','6','7','8','9')";
            //$query_insert_data_test = mysql_query($sql_insert_data_test)or die(mysql_error());
            //echo $sql_insert_data_test;
            //exit();
            //end insert data. 
            ?>
            <form action="new_course_structure.php" method="POST" style="padding-top: 15px;" id="redirect_form">
                <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
                <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
                <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
                <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
                <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
                <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
                <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
                <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/>  
            </form>  
            <script type="text/javascript">
                $("#redirect_form").submit(); // redirect to  new_course_struture.php files. 
            </script>
            <?
        }
        ?>
        <!-- end insertstrueture data  -->
        <!-- เพิ่มโครงสร้าง -->  
        <script type="text/javascript">
            function num_id_val() {
//                alert(num_id);
                //check week 
                if ($("#start_week").val() == "" || $("#stop_week").val() == "") {
                    $("#txtMsg").html("กรุณากรอกสัปดาห์");
                    return false;
                } else if ($("#unit_couse").val() == "") {
                    $("#txtMsg").html("กรุณากรอกหน่วยการเรียนรู้");
                    return false;
                } else if ($("#process").val() == "") {
                    $("#txtMsg").html("กรุณากรอกเนื้อหา");
                    return false;
                } else if ($("#work").val() == "") {
                    $("#txtMsg").html("กรุณากรอกภาระงาน");
                    return false;
                }
//                else if (Number(num_id) == 0) {
//                    $("#txtMsg").html("กรุณาเพิ่มการประเมิน");
//                    return false;
//                } 
                else {
                    $("#num_id_data").val(num_id);
                }

            }
        </script>  
        <form action="" method="POST" enctype="multipart/form-data" id="frmInsertStucture" onsubmit="return num_id_val();">
            <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0"  >
                <tr >
                    <td valign="top" width="15%">สัปดาห์ที่(week)/วัน (summerให้นับเป็นวัน)</td>
                    <td width="85%">
                        <div id="week"> 
                        </div>    
                        <br/>
                        <br/>
                    </td>
                </tr>
<!--                <tr>
                    <td>ชั่วโมง</td>
                    <td>
                        <input type="text" />
                        <br/> 
                        <br/>
                        <br/>
                    </td>
                </tr>-->
                <tr>
                    <td valign="top">หน่วยการเรียนรู้ /Unit</td>
                    <td>
                        <textarea rows="5" cols="100" name="unit_couse" id="unit_couse"></textarea> 
                        <br/> 
                        <br/> 
                    </td>
                </tr>
<!--                <tr>
                    <td valign="top">สาระมาตราฐาน</td>
                    <td>
                        <textarea rows="5" cols="100" name="summary_standard"></textarea><br/> 
                        <br/> 
                    </td>
                </tr>-->
                <tr>
                    <td valign="top">เนื้อหา /Content</td>
                    <td>
                        <textarea rows="5" cols="100" name="process" id="process"></textarea><br/>       <br/> 
                    </td>
                </tr>
<!--                <tr>
                    <td valign="top">จุดประสงค์ที่นำไปสู่ตัวชี้วัด</td>
                    <td>
                        <textarea rows="5" cols="100" name="summary"></textarea><br/>       <br/> 
                    </td>
                </tr>-->
<!--                <tr>
                    <td valign="top">กระบวนการเรียนการสอน</td>
                    <td>
                        <textarea rows="5" cols="100" name="process"></textarea><br/>       <br/> 
                    </td>
                </tr>-->
<!--                <tr>
                    <td valign="top">สื่อ/เทคนิคกระบวนการ</td>
                    <td>
                        <textarea rows="5" cols="100" name="tech"></textarea><br/> 
                        <font color='#555'><i>*อัพได้เฉพาะไฟล์นามสกุล .docx .doc เเละ .pdf</i></font>
                        <br/>
                        <br/>
                        <input type="file" name="tech_file_1"/>  * กิจกรรมก่อนการสอน
                        <br/>
                        <input type="file" name="tech_file_2"/>  * บันทึกหลังการสอน
                        <br/>
                        <input type="file" name="tech_file_3"/>
                        <br/>
                        <br/>
                    </td>
                </tr>-->
<!--                <tr>
                    <td valign="top">สมรรถนะ</td>
                    <td> 
                        <textarea rows="5" cols="100" name="skill"></textarea><br/>       <br/> 
                    </td>
                    </td>
                </tr>-->
<!--                <tr>
                    <td valign="top">คุณลักษณะอันพึงประสงค์</td>
                    <td> 
                        <textarea rows="5" cols="100" name="binifit_school"></textarea><br/>       <br/> 
                    </td>
                    </td>
                </tr>-->
                <tr>
                    <td valign="top">ภาระงาน /Task</td>
                    <td>
                        <textarea rows="5" cols="100" name="work" id="work"></textarea><br/>       <br/> 
                    </td>
                </tr>
                <tr>
                    <td valign="top">เพิ่มข้อสอบ /Test</td>
                    <td>
                         <input  type="file" name="file" /><br>
                    <br>
                    </td>
                </tr>
                <tr>
                    <td valign="top">การวัดผลการเรียนรู้ <br/>
                        Assessment
                    </td>
                    <td>  
                        <script type="text/javascript">
            function insert_score_data(indicator_id) {
                var score = $("#score").val();
                if (isNaN(score) || score <= 0) {
                    $("#dialogError").html("กรุณากรอกคะเเนน");
                    document.getElementById("indicator_check_" + indicator_id).checked = false;
                    document.getElementById("score").select();
                } else {
                    var sum = 0;
                    for (var a = 0; a <= $("#max_row_indicator").val(); a++) {
                        if (document.getElementById("indicator_check_" + a)) {
                            if (document.getElementById("indicator_check_" + a).checked == true) {
                                sum += Number($("#indicator_row_" + a).html());
                            }
                        }
                    }
//                                    alert(sum + " ::" + score);
                    if (Number(sum) <= Number(score)) {
                        var indicator_check = document.getElementById("indicator_check_" + indicator_id).checked;
                        if (indicator_check == true) {
                            var promts = prompt("คะเเนน /Score", 0);
                            if (promts == null) {
                                $("#indicator_row_" + indicator_id).html('0');
                                document.getElementById("indicator_check_" + indicator_id).checked = false;
                            } else if (promts <= 0 || isNaN(promts)) {
                                $("#indicator_row_" + indicator_id).html('0');
                                document.getElementById("indicator_check_" + indicator_id).checked = false;
                            } else {
                                //check data score .
                                if (Number(sum) + Number(promts) <= Number(score)) {
                                    var score = $("#score").val();
                                    $("#indicator_row_" + indicator_id).html(promts);
                                    $("#dialogError").html('');
                                } else {
                                    $("#dialogError").html("ตรวจสอบคะเเนน");
                                    document.getElementById("indicator_check_" + indicator_id).checked = false;
                                }
                            }

                        } else {
                            $("#indicator_row_" + indicator_id).html('0');
                            document.getElementById("indicator_check_" + indicator_id).checked = false;
                        }
                    } else {
                        $("#dialogError").html("ตรวจสอบคะเเนน");
                        document.getElementById("indicator_check_" + indicator_id).checked = false;
                    }
                }
            }
            function addIndicatorNew() {
                $("#dialog-form").dialog("close");
                $("#dialog-indicator").dialog("open");
            }
            function addNewIndicaotor(val, maxidInd) {
                //insert data
                if (val == "") {
                    document.getElementById("addIndicator").select();
                } else {
                    $.ajax({
                        type: "post",
                        url: "new_struture_function/new_struture_function_all.php",
                        data: {type: "insertNewIndicator", val: val, idCourse: "<?= $_POST['subject_id'] ?>",
                            maxidInd: maxidInd, code: '<?= $_POST['subject'] ?>'},
                        success: function(data) {

                            $("#dialog-indicator").html(data);
                        }
                    });
                }
            }
            function delIndicatorNew(id) {
                var com = confirm("ลบข้อมูลนี้  ??");
                //insert data
                if (com == true) {
                    $.ajax({
                        type: "post",
                        url: "new_struture_function/new_struture_function_all.php",
                        data: {type: "delIndicatorNew", id: id, idCourse: "<?= $_POST['subject_id'] ?>"},
                        success: function(data) {

                            $("#dialog-indicator").html(data);
                        }
                    });
                }
            }
            function editIndicatNew(id) {
                $.ajax({
                    type: "post",
                    url: "new_struture_function/new_struture_function_all.php",
                    data: {type: "editIndicatNew", id: id, idCourse: "<?= $_POST['subject_id'] ?>"},
                    success: function(data) {
                        $("#addIndex").html(data);
                    }
                });
            }
            function editIndexNews2(id, txt) {
                $.ajax({
                    type: "post",
                    url: "new_struture_function/new_struture_function_all.php",
                    data: {type: "updateNew2", id: id, idCourse: "<?= $_POST['subject_id'] ?>", txt: txt},
                    success: function(data) {
                        $("#dialog-indicator").html(data);
                    }
                });
            }
                        </script>   
                        <div id="dialog-indicator" style="display: none;" title="เพิ่ม/เเก้ไขตัวชี้วัด Add/Edit Indicator">

                            <fieldset>
                                <legend><b>ลบ/เเก้ไขตัวชี้วัด Delete/Edit Indicator</b></legend>
                                <div style="height: 300px;width: 100%;overflow-y: auto;" id="frmListIndicator">

                                    <table width="100%" cellpadding='0' cellspacing='0'>
                                        <tr>
                                            <th style="border:1px #ddd solid;" width="15%">รหัสตัวชี้วัด (Code)</th>
                                            <th style="border:1px #ddd solid;" width="70%">ชื่อตัวชี้วัด  (Indicator)</th>
                                            <th style="border:1px #ddd solid;" width="25%">การกระทำ (Final Function)</th>
                                        </tr>
                                        <?
                                        $sql_indicator_for_this_course = "select * from evaulation.indicators where ";
                                        $sql_indicator_for_this_course .= " evaulation.indicators.bufferId='" . $_POST['subject_id'] . "'";
                                        $sql_indicator_for_this_course .= " and evaulation.indicators.indicatorscol_delete='0' ";
                                        $sql_indicator_for_this_course .= " order by evaulation.indicators.idMaxCourse asc";
                                        $quer_indicatoe_for_this_course = mysql_query($sql_indicator_for_this_course) or die(mysql_error());
                                        $maxidInd = 1;
                                        if (mysql_num_rows($quer_indicatoe_for_this_course) <> 0) {
                                            while ($row_indicator_for_this_course = mysql_fetch_array($quer_indicatoe_for_this_course)) {
                                                $maxidInd = $row_indicator_for_this_course['idMaxCourse'] + 1;
                                                ?>
                                                <tr>
                                                    <td style="border:1px #ddd solid;" align='center'><?= $row_indicator_for_this_course['indicatorscol_code'] ?></td>
                                                    <td style="border:1px #ddd solid;"><?= $row_indicator_for_this_course['indicatorscol_name'] ?></td>
                                                    <td style="border:1px #ddd solid;" align='center'>
                                                        <input onclick="editIndicatNew('<?= $row_indicator_for_this_course['idindicators'] ?>');" type="button" value=" " title=" เเก้ไข " style="background: url('pic/edit.png');cursor: pointer;"/>
                                                        <input onclick="delIndicatorNew('<?= $row_indicator_for_this_course['idindicators'] ?>');" type="button" value=" " title=" ลบ " style="background: url('pic/delete.gif');cursor: pointer;"/>
                                                    </td>
                                                </tr>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </table> 
                                    <input type="hidden" value="<?= $maxidInd ?>" id="maxidInd" />

                            </fieldset>
                            <br/>
                            <fieldset id="addIndex">
                                <legend><b>เพิ่มตัวชี้วัด /Add Indicator</b></legend> 
                                <table style="width: 100%;">
                                    <tr>
                                        <td width='10%' valign="top">
                                            ชื่อตัวชี้วัด<br/>
                                            Indicator's name
                                        </td>
                                        <td width='80%'>
                                            <textarea cols="100" rows="5" name="addIndicator" id="addIndicator"></textarea>
                                        </td>
                                        <td width='10%'>
                                            <input type="button" value=" เพิ่ม /Add"  onclick="addNewIndicaotor($('#addIndicator').val(), $('#maxidInd').val());"/>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </div>
                        <div id="dialog-form" style="display: none;" title='เพิ่มการวัดผลการเรียนรู้/Add Assessment'> 
                            <table width="100%" style="overflow-y: auto;">
                                <tr>
                                    <td width="20%"><span style="color: red;">*</span><b>ประเมินเรื่อง /Assessment</b></td>
                                    <td width="80%">
                                        <textarea rows="5" cols="100" name="evaulation_title" id="evaulation_title"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td><span style="color: red;">*</span><b>คะเเนน /Score</b></td>
                                    <td>
                                        <input type="text" value="0" style="text-align: center;" size="10" name="score" id="score"/>
                                    </td>    
                                </tr>
                                <tr>
                                    <td><span style="color: red;">*</span><b>ประเภท /Type</b></td>
                                    <td>
                                        <select id="evaulation_type" name="evaulation_type">
                                            <option value="Class work">Class work</option>
                                            <option value="Unit Test">Unit Test</option> 
                                        </select> 
                                    </td>
                                </tr>  
                                <tr> 
                                    <td colspan="2"> 
                                        <fieldset>
                                            <legend><span style="color: red;">*</span><b>ตัวชี้วัด /Indicator</b></legend> 
                                            <?
                                            $arr_Class = explode(",", $_POST['classroom']);
                                            if ($_SESSION['tnameCourse'] == "วิชาเพิ่มเติม" && $arr_Class[0] == "ช่วงชั้นที่ 4" || $arr_Class[0] == "ช่วงชั้นที่ 3") {
                                                ?>
                                                <div>
                                                    <input type="button" value=' เพิ่ม/เเก้ไขตัวชี้วัด Add/Edit Indicator' onclick="addIndicatorNew();"/>
                                                </div>
                                                <hr/>
                                                <?
                                            }
                                            ?> 
                                            <div style="height: 230px; overflow-y: auto;" id="listIndexx">
                                                <table style="width: 100%;">
                                                    <?PHP
                                                    if ($arr_Class[0] == "ช่วงชั้นที่ 4" || $arr_Class[0] == "ช่วงชั้นที่ 3") {
                                                        $type_level = 1;
                                                        $pri = 0;
                                                        $sec = 1;
                                                    } else {
                                                        $type_level = 0;
                                                        $pri = 1;
                                                        $sec = 0;
                                                    }
//                                                    echo $_POST['classroom'];
//                                            echo $arr_Class[0];
                                                    if ($arr_Class[0] == "ช่วงชั้นที่ 4" || $arr_Class[0] == "ช่วงชั้นที่ 3") {
                                                        if ($_SESSION['tnameCourse'] == "วิชาเพิ่มเติม") {
//                                                            $search = array($msubject);
//                                                            $replace = array($msubject . "พ ");
//                                                            $code_addiotion = str_replace($search, $replace, $_post['subject']);
                                                            // $sql_indicator = "select * from indicators where indicatorscol_code like '$code_addiotion%'	 and  indicatorscol_delete ='0' and (indicatorscol_classes ='" . decodeUrl($_GET['classRoom']) . "' or indicatorscol_classes='ม.ปลาย,') and indicatorscol_group ='$msubject' and indicatorscol_isprimary ='$pri' and indicatorscol_secondary ='$sec' and typeCourse ='" . $_SESSION['tnameCourse'] . "'";
//                                                            $sql_indicator = "select * from indicators where    indicatorscol_delete ='0' and (indicatorscol_classes ='" . $_POST['classroom'] . "' or indicatorscol_classes='ม.ปลาย,') and indicatorscol_group ='$msubject' and indicatorscol_isprimary ='$pri' and indicatorscol_secondary ='$sec' and typeCourse ='" . $_SESSION['tnameCourse'] . "'";
                                                            $sql_indicator = "select * from evaulation.indicators where ";
                                                            $sql_indicator .= " evaulation.indicators.bufferId='" . $_POST['subject_id'] . "'";
                                                            $sql_indicator .= " and evaulation.indicators.indicatorscol_delete='0' ";
                                                            $sql_indicator .= " order by evaulation.indicators.idMaxCourse asc";
                                                        } else if ($arr_Class[0] == "ช่วงชั้นที่ 3") {
                                                            $sql_indicator = "select * from indicators where indicatorscol_delete ='0' and (indicatorscol_classes ='" . $_POST['classroom'] . "') and indicatorscol_group ='$msubject' and indicatorscol_isprimary ='$pri' and indicatorscol_secondary ='$sec' and typeCourse ='" . $_SESSION['tnameCourse'] . "'";
                                                        } else if ($arr_Class[0] == "ช่วงชั้นที่ 4") {
                                                            $sql_indicator = "select * from indicators where indicatorscol_delete ='0' and (indicatorscol_classes ='" . $_POST['classroom'] . "' or indicatorscol_classes ='ม.ปลาย,') and indicatorscol_group ='$msubject' and indicatorscol_isprimary ='$pri' and indicatorscol_secondary ='$sec' and typeCourse ='" . $_SESSION['tnameCourse'] . "'";
                                                        }
                                                    } else {
                                                        if ($_POST['term'] == 'ตลอดปี') {
                                                            //get course ID
                                                            $courseIdName = str_split(trim($_POST['subject']));
                                                            $str_course = "";
                                                            $array_str_course = array("ท", "ค", "ว", "ส", "พ", "ศ", "ง", "จ", "อ"
                                                                , "TH", "MA", "SC", "SO", "HP", "AR", "OT", "CH", "EN");
                                                            foreach ($courseIdName as $keysp) {

                                                                if (!is_numeric($keysp)) {
                                                                    //echo $keysp . "<br/>";
                                                                    $str_course .=$keysp;
                                                                }
                                                            }
                                                            //echo $str_course."<br/>";
                                                            foreach ($array_str_course as $idCourseStr) {

                                                                if ($idCourseStr == $str_course) {
                                                                    if ($str_course == 'ท' || $str_course == 'TH') {
                                                                        $msubject = "ท";
                                                                        break;
                                                                    } else if ($str_course == 'ค' || $str_course == 'MA') {
                                                                        $msubject = "ค";
                                                                        break;
                                                                    } else if ($str_course == 'ว' || $str_course == 'SC') {
                                                                        $msubject = "ว";
                                                                        break;
                                                                    } else if ($str_course == 'ส' || $str_course == 'SO') {
                                                                        $msubject = "ส";
                                                                        break;
                                                                    } else if ($str_course == 'พ' || $str_course == 'HP') {
                                                                        $msubject = "พ";
                                                                        break;
                                                                    } else if ($str_course == 'ศ' || $str_course == 'AR') {
                                                                        $msubject = "ศ";
                                                                        break;
                                                                    } else if ($str_course == 'ง' || $str_course == 'AR') {
                                                                        $msubject = "ง";
                                                                        break;
                                                                    } else if ($str_course == 'จ' || $str_course == 'CH') {
                                                                        $msubject = "จ";
                                                                        break;
                                                                    } else if ($str_course == 'อ' || $str_course == 'EN') {
                                                                        $msubject = "อ";
                                                                        break;
                                                                    }
                                                                }
                                                            }
                                                        }
                                                        $sql_indicator = "select * from indicators where indicatorscol_delete ='0' and  ( indicatorscol_classes ='" . $_POST['classroom'] . "' or indicatorscol_classes ='ประถม,' )and indicatorscol_group ='$msubject' and indicatorscol_isprimary ='$pri' and indicatorscol_secondary ='$sec' and typeCourse ='" . $_SESSION['tnameCourse'] . "'";
                                                    }
//                                            echo $sql_indicator;
//find indicator
//                                            $sql_find_indicator = "select * from evaulation.indicators limit 0,50";
                                                    $query_find_indicatoe = mysql_query($sql_indicator) or die(mysql_error());
                                                    $row_indicator = 1;
                                                    while ($row_find_indicator = mysql_fetch_array($query_find_indicatoe)) {
                                                        echo "<td valign='top' width='5%'><input type='checkbox' id='indicator_check_" . $row_indicator . "' onclick='insert_score_data(" . $row_indicator . ");'/></td>";
                                                        echo "<td width='70%'><input type='hidden' id='id_indicator_" . $row_indicator . "' value='" . $row_find_indicator[0] . "' />" . $row_find_indicator[1] . "&nbsp;" . $row_find_indicator[2] . "</td><td  width='25%' valign='top' align='center'><span id='indicator_row_" . $row_indicator . "'>0</span>&nbsp;คะเเนน (Score)</td></tr>";
                                                        $row_indicator++;
                                                    }
                                                    ?>
                                                </table>
                                            </div>
                                        </fieldset>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="hidden" value="<?= $row_indicator ?>" id="max_row_indicator" /></td>
                                    <td></td>
                                </tr> 
                            </table>
                            <div id="dialogError" style="color: red;text-align: center;font-size: large;"></div>
                        </div>
                        <script type="text/javascript">
            function add_evalaution() {
                $("#dialog-form").dialog("open");
                reset_value_form();
                num_id++;
            }
            function reset_value_form() {
                //$("#max_row_indicator").val("0");
                if (document.getElementById("max_row_indicator")) {
                    for (var a = 0; a <= $("#max_row_indicator").val(); a++) {
                        if (document.getElementById("indicator_check_" + a)) {
                            if (document.getElementById("indicator_check_" + a).checked == true) {
                                document.getElementById("indicator_check_" + a).checked = false;
                                $("#indicator_row_" + a).html('0');
                            }
                        }
                    }
                }
                $("#evaulation_title").val("");
                $("#score").val("0");
                $("#dialogError").html();
                //                                $("#file_test").val("");
            }
                        </script>
                        <fieldset>
                            <legend> <input type="button" value=" เพิ่มการวัดผลการเรียนรู้ /Add Assessment" onclick="add_evalaution();"/></legend> 
                            <div style="height: 250px;overflow-y: auto;">
                                <table id="users" width="98%"  align="center" cellpadding="0" cellspacing="0" border="0" >
                                    <thead>
                                        <tr>
                                            <th  align="center" width="70%" style="border:1px #ddd solid;background: #DFECFF;">ประเมินผลเรื่อง<br/>
                                                Assessment
                                            </th>
                                            <th align="center" width="10%" style="border:1px #ddd solid;background: #DFECFF;">คะเเนน<br/>
                                                Score
                                            </th>
                                            <th align="center" width="15%" style="border:1px #ddd solid;background: #DFECFF;">ประเภท<br/>
                                                Type
                                            </th> 
                                            <th align="center" width="5%" style="border:1px #ddd solid;background: #DFECFF;">ลบ
                                                <br/>
                                                Delete
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                    </tbody>
                                </table>
                            </div>
                        </fieldset>
                        <br/>
                    </td>
                </tr>
    <!--                <tr>
                    <td valign="top">อ้างอิง</td>
                    <td>
                        *อัพได้เฉพาะไฟล์รูปภาพนามสกุล .png .gif .jpg เเละ .jpeg 
                        <br/><br/>
                        <input type="file" name="pic1"/><br/>
                        <input type="file" name="pic2"/><br/>
                        <input type="file" name="pic3"/><br/>
                        <input type="file" name="pic4"/><br/>
                        <input type="file" name="pic5"/><br/>
                    </td>
                </tr>-->
                <tr>
                    <td></td>
                    <td>
                        <input type="hidden" name="insert_data_stuture" />
                        <input type="button" value=" เพิ่มข้อมูล /Add Data " onclick="$('#frmInsertStucture').submit();" />&nbsp;
                        <input type="button" value=" ย้อนกลับ /Back" onclick="back_redirect();"/>
                        <br/>
                        <br/>
                        <div style="color: red;"><span id="txtMsg"></span></div>
                    </td>
                </tr>
            </table> 

            <input type="hidden" name="num_id" id="num_id_data" />
            <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
            <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
            <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
            <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
            <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
            <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
            <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
            <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/>  
        </form>  
        <script type="text/javascript">
            function back_redirect() {
                $("#back").submit();
            }
        </script> 
        <form action="new_course_structure.php" method="post" id="back">
            <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
            <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
            <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
            <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
            <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
            <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
            <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
            <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
        </form> 
        <!-- สิ้นสุดเพิ่มโครงสร้าง --> 
        <?php include '../mainsystem/inc_afterdata.php'; ?>
        <?php include '../mainsystem/inc_footer3.php'; ?>
        <?php include '../mainsystem/inc_footer2.php'; ?>
    </body>
</html>
<?php include '../mainsystem/inc_endpage.php'; ?>
<?php
mysql_free_result($rsnews);
mysql_free_result(rscat);
?>