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
mysql_select_db($database_bmks, $bmks);
require_once 'subject.php';
require_once 'encodeUrl/encodeUrl.php';
$charset = "utf-8";
$mime = "text/html";
header("Content-Type: $mime;charset=$charset");

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
                    data: {type: "check_week", year: "<?= $_POST['year'] ?>"},
                    success: function(data) {
                        $("#week").html(data);
                    }
                });
            }
//            check_week();
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
                    height: 600,
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

                                var unit = $("#unit_couse").val();
                                var content = $("#process").val();
                                var jop = $("#work").val();
                                var id_edit_content = "<?= $_POST['id_edit'] ?>";

                                var indicator_text = '';
                                //alert($("#max_row_indicator").val());
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
//                                        $("#users tbody").append("<tr>" +
//                                                "<td align='left' style='border:1px #ddd solid;text-align:justify;'><input type='hidden' name='score" + num_id + "' value='" + $("#score").val() + "' />" + $("#evaulation_title").val() + "</td>" +
//                                                "<td align='center' style='border:1px #ddd solid;'><input type='hidden' name='evaulation_type" + num_id + "' value='" + $("#evaulation_type").val() + "'/>" + $("#score").val() +
//                                                "<input type='hidden'   name='indicator_text" + num_id + "' value='" + indicator_text + "'/></td>" +
//                                                "<td align='center' style='border:1px #ddd solid;'>" + "<input type='hidden' value='" + $("#evaulation_title").val() + "' name='evaulation_title" + num_id + "'/>" + $("#evaulation_type").val() + "</td>" +
//                                                "<td align='center' style='border:1px #ddd solid;'>" + "<input id='row_" + num_id + "' type='button' value=' ' style=\"" + "background:url('pic/delete.gif')" + "\" onclick='delRow(" + num_id + ")'/>" + "</td>" +
//                                                "</tr>"
//                                                );
                                $(this).dialog("close");
                                //insert data new_evaulation_text...
                                $.ajax({
                                    type: "post",
                                    url: "new_struture_function/new_struture_function_all.php",
                                    data: {type: "insert_new_evaulation_text", idCourse: "<?= $_POST['subject_id'] ?>", start_w: $("#start_week").val(),
                                        unit_couse: unit, process: content, work: jop, id_edit: id_edit_content,
                                        stop_w: $("#stop_week").val(), title: $("#evaulation_title").val(), score: $("#score").val(), type_e: $("#evaulation_type").val(), indicator: indicator_text},
                                    success: function(data) {
                                        $("#week").html(data);
                                        $("#frmseft").submit();
                                    }
                                });
//
//                                    } else {
//                                        $("#dialogError").html("กรุณาเลือกตัวชี้วัด");
//                                    }
//                                }
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
                $("#dialog-form-edit").dialog({
                    draggable: false,
                    resizable: false,
                    autoOpen: false,
                    height: 600,
                    width: 1100,
                    modal: false,
                    position: 'top',
                    buttons: {
                        "Edit": function() {
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
                            var evaulation_title2 = $("#evaulation_title2").val();
                            var score2 = $("#score2").val();
                            if (evaulation_title2 == "") {
                                $("#dialogError2").html("กรุณากรอกประเมิน");
                            } else if (isNaN(score2) || score2 < 0) {
                                $("#dialogError2").html("กรุณากรอกคะเเนน");
                            }
                            else if (evaulation_title2 != "" && !isNaN(score2) && score2 > 0) {
                                var indicator_text2 = '';
                                //alert($("#max_row_indicator").val());
                                var status = false;
                                var sum = 0;
                                for (var a = 0; a <= $("#max_row_indicator2").val(); a++) {
                                    if (document.getElementById("indicator_check2_" + a)) {
                                        if (document.getElementById("indicator_check2_" + a).checked == true) {
                                            status = true;
                                            sum += Number($("#indicator_row2_" + a).html());
                                            indicator_text2 += $("#id_indicator2_" + a).val() + "--" + $("#indicator_row2_" + a).html() + "@";
                                        }
                                    }
                                }
//                                if (sum != Number($("#score2").val())) {
//                                    $("#dialogError2").html("รวมคะเเนนตัวชี้วัดไม่เท่ากับคะเเนน");
//                                } else {
//                                    if (status) {
//                                        $("#users tbody").append("<tr>" +
//                                                "<td align='left' style='border:1px #ddd solid;text-align:justify;'><input type='hidden' name='score" + num_id + "' value='" + $("#score").val() + "' />" + $("#evaulation_title").val() + "</td>" +
//                                                "<td align='center' style='border:1px #ddd solid;'><input type='hidden' name='evaulation_type" + num_id + "' value='" + $("#evaulation_type").val() + "'/>" + $("#score").val() +
//                                                "<input type='hidden'   name='indicator_text" + num_id + "' value='" + indicator_text + "'/></td>" +
//                                                "<td align='center' style='border:1px #ddd solid;'>" + "<input type='hidden' value='" + $("#evaulation_title").val() + "' name='evaulation_title" + num_id + "'/>" + $("#evaulation_type").val() + "</td>" +
//                                                "<td align='center' style='border:1px #ddd solid;'>" + "<input id='row_" + num_id + "' type='button' value=' ' style=\"" + "background:url('pic/delete.gif')" + "\" onclick='delRow(" + num_id + ")'/>" + "</td>" +
//                                                "</tr>"
//                                                );
                                $(this).dialog("close");
                                //updatet data new_evaulation_text...
                                $.ajax({
                                    type: "post",
                                    url: "new_struture_function/new_struture_function_all.php",
                                    data: {type: "update_new_evaulation_text2", id_txt_edit: $("#id_txt_edit").val(),
                                        indicator: indicator_text2, type_s: $("#evaulation_type2").val(), title: $("#evaulation_title2").val(),
                                        score: $("#score2").val()},
                                    success: function(data) {
//                                                alert("test" + data);
                                        $("#frmseft").submit();
                                    }
                                });

//                                    } else {
//                                        $("#dialogError2").html("กรุณาเลือกตัวชี้วัด");
//                                    }
//                                }
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
            });
            function delRow(id) {
                var confirmDel = confirm(" ลบข้อมูลนี้ ??");
                if (confirmDel) {
                    $("#row_" + id).parent().parent().remove();
                }
            }
            function delRow2(id, id_auto) {
                var confirmDel = confirm(" ลบข้อมูลนี้ ??");
                if (confirmDel) {
                    $("#row_" + id).parent().remove();
                    //update data..
                    $.ajax({
                        type: "post",
                        url: "new_struture_function/new_struture_function_all.php",
                        data: {type: "update_new_evaulation_text", id_auto: id_auto},
                        success: function() {

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
            if (!empty($file_pic)) {
                $type_file_pic = explode(".", $file_pic);
                $end_file_pic = end($type_file_pic);
                if ($end_file_pic == "png" || $end_file_pic == "jpeg" || $end_file_pic == "gif" || $end_file_pic == "jpg") {
                    $rename_file_pic = rename_files('3') + 1;
                    copy($_FILES['pic' . $num_file]['tmp_name'], 'new_files_images/' . $rename_file_pic . "." . $end_file_pic);
                    $width = 100; //*** Fix Width & Heigh (Autu caculate) ***//
                    $size = GetimageSize($_FILES['pic' . $num_file]['tmp_name']);
                    $height = round($width * $size[1] / $size[0]);
                    $images_orig = ImageCreateFromJPEG($_FILES['pic' . $num_file]['tmp_name']);
                    $photoX = ImagesX($images_orig);
                    $photoY = ImagesY($images_orig);
                    $images_fin = ImageCreateTrueColor($width, $height);
                    ImageCopyResampled($images_fin, $images_orig, 0, 0, 0, 0, $width + 1, $height + 1, $photoX, $photoY);
                    ImageJPEG($images_fin, "new_files_images/" . $rename_file_pic . "." . $end_file_pic);
                    ImageDestroy($images_orig);
                    ImageDestroy($images_fin);
                    update_num_max($rename_file_pic, '3');
                }
            }
            return $rename_file_pic . "." . $end_file_pic;
        }

        //save file doc and pdf.
        function save_file_doc_pdf($file_doc_pdf, $num_file) {
            //check type files. 
            $rename_files = '';
            if (!empty($file_doc_pdf)) {
                $type_file = explode(".", $file_doc_pdf);
                $end_type_file = end($type_file);
                if ($end_type_file == "doc" || $end_type_file == "docx" || $end_type_file == "pdf") {
                    $rename_files = rename_files('1') + 1;
                    copy($_FILES['tech_file_' . $num_file]["tmp_name"], "new_files_source/" . $rename_files . '.' . $end_type_file);
                    update_num_max($rename_files, '1');
                }
            }
            return $rename_files . '.' . $end_type_file;
        }

        function save_file_doc($file_doc, $num_file) {
            //check type files. 
            $rename_files = '';
            if (!empty($file_doc)) {
                $type_file = explode(".", $file_doc);
                $end_type_file = end($type_file);
                if ($end_type_file == "doc" || $end_type_file == "docx") {
                    $rename_files = rename_files('2') + 1;
                    copy($_FILES['file_test' . $num_file]["tmp_name"], "new_files_test/" . $rename_files . '.' . $end_type_file);
                    update_num_max($rename_files, '2');
                }
            }
            return $rename_files . '.' . $end_type_file;
        }

        if (isset($_POST['insert_data_stuture'])) {
            //varible files technich data.
//            $file_tech1 = save_file_doc_pdf($_FILES['tech_file_1']['name'], '1');
//            $file_tech2 = save_file_doc_pdf($_FILES['tech_file_2']['name'], '2');
//            $file_tech3 = save_file_doc_pdf($_FILES['tech_file_3']['name'], '3');
//
//
//            //varble picture data. 
//            $pic1 = save_image_picture($_FILES['pic1']['name'], '1');
//            $pic2 = save_image_picture($_FILES['pic2']['name'], '2');
//            $pic3 = save_image_picture($_FILES['pic3']['name'], '3');
//            $pic4 = save_image_picture($_FILES['pic4']['name'], '4');
//            $pic5 = save_image_picture($_FILES['pic5']['name'], '5');
//
//            //insert indicator data.
//            $max_evaulation_num = $_POST['num_id'];
//            for ($jj = 0; $jj <= $max_evaulation_num; $jj++) {
//                if (isset($_POST['evaulation_title' . $jj])) {
//                    $title = $_POST['evaulation_title' . $jj];
//                    $indecator_text = $_POST['indicator_text' . $jj];
//                    $evaulation_type = $_POST['evaulation_type' . $jj];
//                    $score = $_POST['score' . $jj];
//                    $file_test = save_file_doc($_FILES['file_test' . $jj]['name'], $jj);
//                    $sql_insert_new = "insert into evaulation.new_evaulation_text values ('','" . $_POST['subject_id'] . "','" . $_POST['start_week'] . "','" . $_POST['stop_week'] . "','$title','$score','$evaulation_type','" . $indecator_text . "','$file_test','');";
//                    $query_insert_new = mysql_query($sql_insert_new) or die(mysql_error());
//                }
//            }
//            //end insert indicator data.
//            //
//            //insert data. 
//            $sql_insert_data_struture = "insert into evaulation.new_course_struture values ('','" . $_POST['subject_id'] . "','" . $_POST['start_week'] . "','" . $_POST['stop_week'] . "','" . addslashes($_POST['unit_couse']) . "',";
//            $sql_insert_data_struture.="'" . addslashes($_POST['summary']) . "','" . addslashes($_POST['process']) . "','" . addslashes($_POST['tech']) . "','$file_tech1','$file_tech2',";
//            $sql_insert_data_struture.="'$file_tech3','" . addslashes($_POST['work']) . "','$pic1','$pic2',";
//            $sql_insert_data_struture.="'$pic3','$pic4','$pic5','0');";
//            $query_insert_data_struture = mysql_query($sql_insert_data_struture) or die(mysql_error());
            //end insert data. 

            $update_new_course_struture = " update evaulation.new_course_struture set ";
            $update_new_course_struture .=" evaulation.new_course_struture.unit_course='" . addslashes($_POST['unit_couse']) . "',";
            $update_new_course_struture .=" evaulation.new_course_struture.process_course='" . addslashes($_POST['process']) . "',";
            $update_new_course_struture .=" evaulation.new_course_struture.works='" . addslashes($_POST['work']) . "'";
            $update_new_course_struture .=" where evaulation.new_course_struture.id_auto='" . $_POST['id_edit'] . "'";
            mysql_query($update_new_course_struture) or die(mysql_error());
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
        <?php

        function replace_str($string) {
            return str_replace("//", "", $string);
        }

        //find data id edit.......
        $sql_edit_structure_course = "select  * from evaulation.new_course_struture ";
        $sql_edit_structure_course .=" where evaulation.new_course_struture.id_auto = '" . $_POST['id_edit'] . "'";
        $query_edit_structure_course = mysql_query($sql_edit_structure_course) or die(mysql_error());
        $row_edit_structure_course = mysql_fetch_array($query_edit_structure_course);

        //end find data id edit ...
        ?>
        <form action="" method="POST" enctype="multipart/form-data" onsubmit="return num_id_val();" id="frmEdit">
            <table width="98%" border="0" align="center" cellpadding="0" cellspacing="0"  >
                <tr >
                    <td valign="top" width="15%">สัปดาห์ที่(week)/วัน(summer)</td>
                    <td width="85%">
                        <script type="text/javascript">
//                            function editWeekCourseStructure() {
//                                $.ajax({
//                                    type: "post",
//                                    url: "new_struture_function/new_struture_function_all.php",
//                                    data: {idCourse: "<?= $_POST['subject_id'] ?>", type: "editWeekStructure", term: "<?= $_POST['term']; ?>", startW: "<?= $row_edit_structure_course['start_w']; ?>"
//                                        , stopW: "<?= $row_edit_structure_course['stop_w']; ?>"},
//                                    success: function(data) {
////                                        alert(data);
//                                        $("#editWeek").html(data);
//                                    }
//                                });
//                            }
//                            editWeekCourseStructure();
//                            function findStopWeek(start) {
//                                $.ajax({
//                                    type: "post",
//                                    url: "new_struture_function/new_struture_function_all.php",
//                                    data: {type: "findStopWeek", idCourse: "<?= $_POST['subject_id'] ?>", start: start, year: "<?= $_POST['term'] ?>",
//                                        stopW: "<?= $row_edit_structure_course['stop_w']; ?>"},
//                                    success: function(data) {
//                                        $("#stop_week").html(data);
//                                    }
//                                });
//                            }

                        </script>
                        <!--<div id="editWeek"></div>-->
                        <?= $row_edit_structure_course['start_w']; ?>&nbsp;-&nbsp;<?= $row_edit_structure_course['stop_w']; ?>
                        <input type="hidden" name="start_week" id="start_week" value="<?= $row_edit_structure_course['start_w']; ?>"/>
                        <input type="hidden" name="stop_week" id="stop_week" value="<?= $row_edit_structure_course['stop_w']; ?>"/>
                        <br/>
                        <br/>
                    </td>
                </tr>
                <tr>
                    <td valign="top">หน่วยการเรียนรู้ /Unit</td>
                    <td>
                        <textarea rows="5" cols="100" id="unit_couse" name="unit_couse"><?= replace_str($row_edit_structure_course['unit_course']); ?></textarea> 
                        <br/> 
                    </td>
                </tr>
                <tr>
                    <td valign="top">เนื้อหา /Content</td>
                    <td>
                        <textarea rows="5" cols="100" name="process" id="process"><?= replace_str($row_edit_structure_course['process_course']); ?></textarea><br/> 
                    </td>
                </tr>
<!--                <tr>
                    <td valign="top">กระบวนการเรียนการสอน</td>
                    <td>
                        <textarea rows="5" cols="100" name="process"><?= replace_str($row_edit_structure_course['process_course']); ?></textarea><br/> 
                    </td>-->
                <!--                </tr>
                                <tr>
                                    <td valign="top">สื่อ/เทคนิคกระบวนการ</td>
                                    <td>
                                        <textarea rows="5" cols="100" name="tech"><?= replace_str($row_edit_structure_course['technich_text']); ?></textarea><br/> 
                                        <font color='#555'><i>*อัพได้เฉพาะไฟล์นามสกุล .docx .doc เเละ .pdf</i></font>
                                        <br/>
                                        <br/>
                                        <input type="file" name="tech_file_1"/>
                                        <br/>
                                        <input type="file" name="tech_file_2"/>
                                        <br/>
                                        <input type="file" name="tech_file_3"/>
                                        <br/>
                                        <br/>
                                    </td>
                                </tr>-->
                <tr>
                    <td valign="top">ภาระงาน /Task</td>
                    <td>
                        <textarea rows="5" cols="100" id="work" name="work"><?= replace_str($row_edit_structure_course['works']); ?></textarea><br><br/> 
                    </td>
                </tr>
                
                <tr>
                    <td valign="top">การวัดผลการเรียนรู้<br/>
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
                            function insert_score_data2(indicator_id) {
                                var score2 = $("#score2").val();
                                if (isNaN(score2) || score2 <= 0) {
                                    $("#dialogError2").html("กรุณากรอกคะเเนน");
                                    document.getElementById("indicator_check2_" + indicator_id).checked = false;
                                    document.getElementById("score2").select();
                                } else {
                                    var sum2 = 0;
                                    for (var a = 0; a <= $("#max_row_indicator2").val(); a++) {
                                        if (document.getElementById("indicator_check2_" + a)) {
                                            if (document.getElementById("indicator_check2_" + a).checked == true) {
                                                sum2 += Number($("#indicator_row2_" + a).html());
                                            }
                                        }
                                    }
//                                    alert(sum + " ::" + score);
                                    if (Number(sum2) <= Number(score2)) {
                                        var indicator_check = document.getElementById("indicator_check2_" + indicator_id).checked;
                                        if (indicator_check == true) {
                                            var promts = prompt("คะเเนน /Score", 0);
                                            if (promts == null) {
                                                $("#indicator_row2_" + indicator_id).html('0');
                                                document.getElementById("indicator_check2_" + indicator_id).checked = false;
                                            } else if (promts <= 0 || isNaN(promts)) {
                                                $("#indicator_row2_" + indicator_id).html('0');
                                                document.getElementById("indicator_check2_" + indicator_id).checked = false;
                                            } else {
                                                //check data score .
                                                if (Number(sum2) + Number(promts) <= Number(score2)) {
                                                    var score2 = $("#score2").val();
                                                    $("#indicator_row2_" + indicator_id).html(promts);
                                                    $("#dialogError2").html('');
                                                } else {
                                                    $("#dialogError2").html("ตรวจสอบคะเเนน");
                                                    document.getElementById("indicator_check2_" + indicator_id).checked = false;
                                                }
                                            }

                                        } else {
                                            $("#indicator_row2_" + indicator_id).html('0');
                                            document.getElementById("indicator_check2_" + indicator_id).checked = false;
                                        }
                                    } else {
                                        $("#dialogError2").html("ตรวจสอบคะเเนน");
                                        document.getElementById("indicator_check2_" + indicator_id).checked = false;
                                    }
                                }
                            }
                        </script>

                        <div id="dialog-form" style="display: none;"  title='เพิ่มการวัดผลการเรียนรู้ /Add assessment'> 
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
                                            <div style="height: 230px; overflow-y: auto;">
                                                <table style="width: 100%;">
                                                    <?PHP
//                                                    $check_subject = "select * evaulation.subject where evaulation.subject.subjectID ='" . $_POST['subject_id'] . "'";
//                                                    $query_check = mysql_query($check_subject) or die(mysql_error());
//                                                    $data_check = mysql_fetch_array($query_check);
                                                    $arr_Class = explode(",", $_POST['classroom']);
                                                    if ($arr_Class[0] == "ช่วงชั้นที่ 4" || $arr_Class[0] == "ช่วงชั้นที่ 3") {
                                                        $type_level = 1;
                                                        $pri = 0;
                                                        $sec = 1;
                                                    } else {
                                                        $type_level = 0;
                                                        $pri = 1;
                                                        $sec = 0;
                                                    }

//                                                    if ($type_level == 0) {
//                                                        $pri = 1;
//                                                        $sec = 0;
//                                                    } else {
//                                                        $pri = 0;
//                                                        $sec = 1;
//                                                    }
//                                                    echo $_POST['classroom'];
//                                            echo $arr_Class[0];
                                                    if ($arr_Class[0] == "ช่วงชั้นที่ 4" || $arr_Class[0] == "ช่วงชั้นที่ 3") {
                                                        if ($_SESSION['tnameCourse'] == "วิชาเพิ่มเติม") {
                                                            $search = array($msubject);
                                                            $replace = array($msubject . "พ ");
                                                            $code_addiotion = str_replace($search, $replace, $_post['subject']);
                                                            // $sql_indicator = "select * from indicators where indicatorscol_code like '$code_addiotion%'	 and  indicatorscol_delete ='0' and (indicatorscol_classes ='" . decodeUrl($_GET['classRoom']) . "' or indicatorscol_classes='ม.ปลาย,') and indicatorscol_group ='$msubject' and indicatorscol_isprimary ='$pri' and indicatorscol_secondary ='$sec' and typeCourse ='" . $_SESSION['tnameCourse'] . "'";

                                                            $sql_indicator = "select * from evaulation.indicators where ";
                                                            $sql_indicator .= " evaulation.indicators.bufferId='" . $_POST['subject_id'] . "'";
                                                            $sql_indicator .= " and evaulation.indicators.indicatorscol_delete='0' ";
                                                            $sql_indicator .= " order by evaulation.indicators.idMaxCourse asc";
//                                                            $sql_indicator = "select * from indicators where    indicatorscol_delete ='0' and (indicatorscol_classes ='" . $_POST['classroom'] . "' or indicatorscol_classes='ม.ปลาย,') and indicatorscol_group ='$msubject' and indicatorscol_isprimary ='$pri' and indicatorscol_secondary ='$sec' and typeCourse ='" . $_SESSION['tnameCourse'] . "'";
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
                                                        echo "<td width='70%'><input type='hidden' id='id_indicator_" . $row_indicator . "' value='" . $row_find_indicator[0] . "' />" . $row_find_indicator[1] . "&nbsp;" . $row_find_indicator[2] . "</td><td  width='25%' valign='top' align='center'><span id='indicator_row_" . $row_indicator . "'>0</span>&nbsp;คะเเนน(Score)</td></tr>";
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
                        <div id="dialog-form-edit" style="display: none;" title='เเก้ไขการวัดผลการเรียนรู้ /Edit assessment'>

                        </div>
                        <script type="text/javascript">

                            function edit_evalaution(id_edit_val) {
                                var unit = $("#unit_couse").val();
                                var content = $("#process").val();
                                var jop = $("#work").val();
                                var id_edit_content = "<?= $_POST['id_edit'] ?>";
                                $.ajax({
                                    type: "post",
                                    url: "new_struture_function/new_struture_function_all.php",
                                    data: {type: "edit_evaulatons", id_edit_val: id_edit_val, classroom: "<?= $_POST['classroom'] ?>",
                                        unit_couse: unit, process: content, work: jop, id_edit: id_edit_content,
                                        type_level: "<?= $type_level ?>", msubject: "<?= $msubject ?>", tnameCourse: "<?= $_SESSION['tnameCourse'] ?>", idCourse: "<?= $_POST['subject_id']; ?>"},
                                    success: function(data) {
//                                        alert(data);
//                                        $("#txt").html(data);
                                        $("#dialog-form-edit").html(data);
                                        $("#dialog-form-edit").dialog("open");
                                    }
                                });
                                reset_value_form();
                                num_id++;
                            }
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
                        <div id="txt"></div>
                        <fieldset>
                            <legend> <input type="button" value=" เพิ่มการวัดผลการเรียนรู้ /Add assessment" onclick="add_evalaution();"/></legend> 
                            <div style="height: 250px;overflow-y: auto;">
                                <table id="users" width="98%"  align="center" cellpadding="0" cellspacing="0" border="0" >
                                    <thead>
                                        <tr>
                                            <th  align="center" width="65%" style="border:1px #ddd solid;background: #DFECFF;">ประเมินผลเรื่อง<br/>
                                                Assessment
                                            </th>
                                            <th align="center" width="10%" style="border:1px #ddd solid;background: #DFECFF;">คะเเนน<br/>
                                                Score
                                            </th>
                                            <th align="center" width="15%" style="border:1px #ddd solid;background: #DFECFF;">ประเภท
                                                <br/>Type
                                            </th> 
                                            <th align="center" width="10%" style="border:1px #ddd solid;background: #DFECFF;">เเก้ไข/ลบ
                                                <br/>Edit/Delete
                                            </th>
                                        </tr>
                                    </thead> 
                                    <tbody> 
                                        <?php
                                        $sql_find_new_evaulation_text = "select * from evaulation.new_evaulation_text";
                                        $sql_find_new_evaulation_text .= " where evaulation.new_evaulation_text.id_course ='" . $_POST['subject_id'] . "'";
                                        $sql_find_new_evaulation_text .= " and evaulation.new_evaulation_text.start_w ='" . $row_edit_structure_course['start_w'] . "'";
                                        $sql_find_new_evaulation_text .= " and evaulation.new_evaulation_text.stop_w='" . $row_edit_structure_course['stop_w'] . "'";
                                        $sql_find_new_evaulation_text .= " and evaulation.new_evaulation_text.delete ='0'";
                                        $sql_find_new_evaulation_text .= " order by evaulation.new_evaulation_text.id_auto_eva_text asc";
                                        $query_find_new_evaulation_text = mysql_query($sql_find_new_evaulation_text) or die(mysql_error());
                                        $id_find_new_evaulation_text = 0;
                                        while ($row_find_new_evaulation_text = mysql_fetch_array($query_find_new_evaulation_text)) {
                                            ?>
                                            <tr>
                                                <td id="row_<?= $id_find_new_evaulation_text ?>" align="left" style="text-align: justify;border:1px #ddd solid;"><?= $row_find_new_evaulation_text['title'] ?></td>
                                                <td align="center" style="border:1px #ddd solid;"><?= $row_find_new_evaulation_text['score'] ?></td>
                                                <td align="center" style="border:1px #ddd solid;"><?= $row_find_new_evaulation_text['type'] ?></td>
                                                <td  align="center" style="border:1px #ddd solid;">
                                                    <input type="button" value=" " title=" เเก้ไข " style="background: url('pic/edit.png');cursor: pointer;" onclick="edit_evalaution('<?= $row_find_new_evaulation_text['id_auto_eva_text'] ?>');"/>
                                                    &nbsp;
                                                    <input onclick="delRow2('<?= $id_find_new_evaulation_text ?>', '<?= $row_find_new_evaulation_text['id_auto_eva_text'] ?>');" type="button" value=" " title=" ลบ " style="background: url('pic/delete.gif');cursor: pointer;"/>

                                                </td>
                                            </tr>     
                                            <?
                                            $id_find_new_evaulation_text++;
                                        }
                                        ?> 
                                    </tbody> 
                                </table>
                                <script type="text/javascript">
                            num_id = Number(<?= $id_find_new_evaulation_text ?>);
                                </script>
                            </div>
                        </fieldset>
                        <br/>
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
                        <input type="button" value=" เเก้ไขข้อมูล /Edit"  onclick="$('#frmEdit').submit();"/>&nbsp;<input type="button" value=" ย้อนกลับ /Back" onclick="back_redirect();"/>
                        <br/>
                        <br/>
                        <div style="color: red;"><span id="txtMsg"></span></div>
                    </td> 
                </tr>
            </table> 
            <br/>
            <br/> 
            <input type="hidden" name="id_edit" value="<?= $_POST['id_edit']; ?>" />
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
        <form action="<?= $_PHP_SELF ?>" method="post" id="frmseft">
            <input type="hidden" name="id_edit" value="<?= $_POST['id_edit']; ?>" />
            <input type="hidden" name="subject_id" value="<?= $_POST['subject_id'] ?>" />
            <input type="hidden" name="subject" value="<?= $_POST['subject']; ?>"/>
            <input type="hidden" name="subject_name" value="<?= $_POST['subject_name']; ?>"/>
            <input type="hidden" name="unit" value="<?= $_POST['unit']; ?>"/>
            <input type="hidden" name="term" value="<?= $_POST['term']; ?>"/>
            <input type="hidden" name="year" value="<?= $_POST['year']; ?>"/>
            <input type="hidden" name="classroom" value="<?= $_POST['classroom']; ?>"/>
            <input type="hidden" name="tname" value="<?= $_POST['tname']; ?>"/> 
        </form> 
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
mysql_close();
?>