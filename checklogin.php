<?php require_once('Connections/bmks.php'); ?>
<?php require_once('Connections/bmks2.php'); ?>
<meta charset="utf-8" />
<?
session_start();

function chkBrowser($nameBroser) {
    return preg_match("/" . $nameBroser . "/", $_SERVER['HTTP_USER_AGENT']);
}

if (chkBrowser("MSIE") == 1) {
    echo "<br/>";
    echo "<br/>";
    echo "<br/>";
    ?>
    <div style="width: 1000px;heigth:300px;background: #ddd;">
        <br/>
        <?
        echo "<center style='color:red;'>ระบบตรวจพบว่า Web browser ของคุณคือ Internet Explorer ( IE ) หากคุณต้องการใช้งานให้คุณใช้ Web browser <font color='blue'>Chrome</font> หรือ <font color='blue'>Fire fox</font> </center>";
        echo "<br/>";
        echo "<center style='color:red;'> The system has detected that your Web browser is Internet Explorer (IE), if you want to use a Web browser <font color='blue'>Chrome</font> or <font color='blue'>Fire fox</font> .</center>";
        ?>
        <br/>
    </div>
    <?
    echo "<br/>";
    echo "<br/>";
    echo "<br/>";
} else {


    $usertype = addslashes($_POST['usertype']);
    $username = addslashes($_POST['username']);
    $password = addslashes($_POST['password']);
    $captcha = addslashes($_POST['captcha']);
    $cap_code = addslashes($_SESSION['cap_code']);

	/*
	$usertype =$_POST['usertype'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $captcha = $_POST['captcha'];
    $cap_code = $_SESSION['cap_code'];
	
	echo "".$captcha."<br>".$cap_code;
*/

    if ($captcha <> $cap_code) { //กรณีกรอก Captcha ไม่ตรง
        $updateGoTo = "login.php?tw=1";
        echo "<script>location.href='" . $updateGoTo . "'</script>";
        exit();
    }
    if ($username == "" or $password == "") {//กรณีป้อนข้อมูลไม่ครบ
        $updateGoTo = "login.php?tw=2";
        echo "<script>location.href='" . $updateGoTo . "'</script>";
        exit();
    }
//เช็ครหัสพนักงาน กับ รหัส และต้องมาจาก Rec พนักงานจริงๆ ไม่ใช่ add ข้ามแผนก
    $passwordm = md5($password);
    mysql_select_db($database_bmks, $bmks);
    $query_rscheckx = "SELECT * FROM loguser WHERE ECODE ='$username' AND loguser_password='$passwordm' AND loguser_status IS NULL";
    $rscheckx = mysql_query($query_rscheckx, $bmks) or die(mysql_error());
    $row_rscheckx = mysql_fetch_assoc($rscheckx);
    $totalRows_rscheckx = mysql_num_rows($rscheckx);


    if ($totalRows_rscheckx <> "") { //กรณี Login ผ่าน
        //ให้ตรวจสอบ Permission
        mysql_select_db($database_bmks, $bmks);
        //$query_rscheckxx = "SELECT * FROM loguser WHERE ECODE ='$username'";
        $query_rscheckxx = "SELECT * FROM loguser INNER JOIN logper ON loguser.logper_id=logper.logper_id WHERE loguser.ECODE='$username'";
        //อิงตาราง publishersinfo  เป็นหลัก
        $rscheckxx = mysql_query($query_rscheckxx, $bmks) or die(mysql_error());
        $row_rscheckxx = mysql_fetch_assoc($rscheckxx);
        $totalRows_rscheckxx = mysql_num_rows($rscheckxx);
        $logper_namer = "";
        do {
            $logper_name = $row_rscheckxx["logper_name"];
            $logper_type1 = $row_rscheckxx["logper_type1"]; //หน้าดูคะแนน
            $logper_type2 = $row_rscheckxx["logper_type2"]; //หน้าแก้ไขคะแนน
            $logper_type3 = $row_rscheckxx["logper_type3"]; //ออกรายงาน
            $logper_type4 = $row_rscheckxx["logper_type4"]; //หน้า Admin
            $logper_type5 = $row_rscheckxx["logper_type5"]; //หน้าดูคะแนนทั้งหมด
            $logper_type6 = $row_rscheckxx["logper_type6"]; //หน้าโครงสร้างเนื้อหาการเรียนรู้
            $logper_type7 = $row_rscheckxx["logper_type7"]; //ประเมินการคุณลักษณะระดับมัธยม
            $logper_type8 = $row_rscheckxx["logper_type8"]; //ประเมินการอ่าน คิด วิเคราะห์ระดับมัธยม
            $logper_type9 = $row_rscheckxx["logper_type9"]; //ระบบรายงาน การอ่านคิดวิเคราะห์ และเขียนรายปี ระดับมัธยมศึกษา
            $logper_type10 = $row_rscheckxx["logper_type10"]; //ทะเบียนนักเรียน

            $logper_type13 = $row_rscheckxx["logper_type13"]; //บันทึกการอ่านคิดและคุณลักษณะประถม และข้อมูลชุมมุม
            $logper_type14 = $row_rscheckxx["logper_type14"]; //รายงานการอ่านคิดและคุณลักษณะประถม

            $logper_type15 = $row_rscheckxx["logper_type15"]; //สรุปรายปีการอ่านคิดวิเคราะห์ สำหรับประถม
            $logper_type16 = $row_rscheckxx["logper_type16"]; //สรุปรายปีคุณลักษณะ สำหรับประถม
			$logper_type17 = $row_rscheckxx["logper_type17"]; //ระบบปกครอง
            $logper_type18 = $row_rscheckxx["logper_type18"];//ระบบจัดการข้อสอบ


            if ($logper_type1r <> "2")
                $logper_type1r = $logper_type1;
            if ($logper_type2r <> "2")
                $logper_type2r = $logper_type2;
            if ($logper_type3r <> "2")
                $logper_type3r = $logper_type3;
            if ($logper_type4r <> "2")
                $logper_type4r = $logper_type4;
            if ($logper_type5r <> "2")
                $logper_type5r = $logper_type5;
            if ($logper_type6r <> "2")
                $logper_type6r = $logper_type6;
            if ($logper_type7r <> "2")
                $logper_type7r = $logper_type7;
            if ($logper_type8r <> "2")
                $logper_type8r = $logper_type8;
            if ($logper_type9r <> "2")
                $logper_type9r = $logper_type9;
            if ($logper_type10r <> "2")
                $logper_type10r = $logper_type10;

            if ($logper_type13r <> "2")
                $logper_type13r = $logper_type13;
            if ($logper_type14r <> "2")
                $logper_type14r = $logper_type14;

            if ($logper_type15r <> "2")
                $logper_type15r = $logper_type15;
            if ($logper_type16r <> "2")
                $logper_type16r = $logper_type16;
            if ($logper_type17r <> "2") //ระบบปกครอง
                $logper_type17r = $logper_type17;
            if ($logper_type18r <> "2") //ระบบปกครอง
                $logper_type18r = $logper_type18;

            if ($logper_namer == "") //กรณีมีหลายฝ่ายในคนๆ เดียว
                $logper_namer = $logper_name;
            else
                $logper_namer = $logper_namer . " , " . $logper_name;
        } while ($row_rscheckxx = mysql_fetch_assoc($rscheckxx));


        //เรียกชื่อมาเก็บเป็น Session ไว้
        mysql_select_db($database_bmks2, $bmks2);
        $query_rscheckz = "SELECT * FROM employee_t WHERE ECODE ='$username'";
        $rscheckz = mysql_query($query_rscheckz, $bmks2) or die(mysql_error());
        $row_rscheckz = mysql_fetch_assoc($rscheckz);
        $totalRows_rscheckz = mysql_num_rows($rscheckz);


        $ENAME = $row_rscheckz["ENAME"];
        $ESNAME = $row_rscheckz["ESNAME"];
		$STATUS= $row_rscheckz["STATUS"];
		
		if($STATUS<>"ทำงาน") {
			$updateGoTo = "login.php?tw=4";
			echo "<script>location.href='" . $updateGoTo . "'</script>";
			exit();
		}

        $_SESSION["login_name"] = $ENAME . " " . $ESNAME;
        $_SESSION["login_typename"] = $logper_namer;
        $_SESSION["logper_type1r"] = $logper_type1r;
        $_SESSION["logper_type2r"] = $logper_type2r;
        $_SESSION["logper_type3r"] = $logper_type3r;
        $_SESSION["logper_type4r"] = $logper_type4r;
        $_SESSION["logper_type5r"] = $logper_type5r;
        $_SESSION["logper_type6r"] = $logper_type6r;
        $_SESSION["logper_type7r"] = $logper_type7r;
        $_SESSION["logper_type8r"] = $logper_type8r;
        $_SESSION["logper_type9r"] = $logper_type9r;
        $_SESSION["logper_type10r"] = $logper_type10r;

        $_SESSION["logper_type13r"] = $logper_type13r;
        $_SESSION["logper_type14r"] = $logper_type14r;

        $_SESSION["logper_type15r"] = $logper_type15r;
        $_SESSION["logper_type16r"] = $logper_type16r;
		$_SESSION["logper_type17r"] = $logper_type17r; //ระบบปกครอง
        $_SESSION["logper_type18r"] = $logper_type18r;//ระบบจัดการข้อสอบ
        $_SESSION["login_ecode"] = $username;

        mysql_select_db($database_bmks, $bmks);
        $query_rscheckzd = "SELECT * FROM loguser WHERE ECODE ='$username' AND loguser_status IS NULL";
        $rscheckzd = mysql_query($query_rscheckzd, $bmks) or die(mysql_error());
        $row_rscheckzd = mysql_fetch_assoc($rscheckzd);
        $totalRows_rscheckzd = mysql_num_rows($rscheckzd);
        $department = $row_rscheckzd["loguser_department"];
        $_SESSION["department"] = $department; //เก็บตัวแปรสำหรับกำหนดระดับชั้น 0=ไม่ได้กำหนด 1=ทุกระดับชั้น 2=ประถม 3=มัธยม
        //บันทึก Log การ Login
        // 1 -  คือ เริ่ม Login
        mysql_select_db($database_bmks, $bmks);
        $myip = $_SERVER['REMOTE_ADDR'];
        $query_neoxe = "INSERT INTO savelog(ECODE,savelog_type,savelog_ip) VALUES('$username','1','$myip')"; //1 login
        $neoxe = mysql_query($query_neoxe, $bmks) or die(mysql_error());


        $updateGoTo = "index.php";
        echo "<script>location.href='" . $updateGoTo . "'</script>";
        exit();
    }
    else { //กรณีไม่เจอ
        $updateGoTo = "login.php?tw=4";
        echo "<script>location.href='" . $updateGoTo . "'</script>";
        exit();
    }

    mysql_free_result($rscheckx);
    mysql_free_result($rscheckz);
    mysql_free_result($rscheckxx);
    mysql_free_result($rscheckzd);
    ?>
    <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
    <html xmlns="http://www.w3.org/1999/xhtml">
        <head>
            <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
            <title>Please wait...</title>
        </head>

        <body>
            Please wait...
        </body>
    </html>
    <?
}
?>